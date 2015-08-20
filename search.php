<?php
require 'workflow.php';
require 'functions.php';
require 'GoogleMapClient.php';

isset ( $argv [1] ) && $query = trim ( $argv [1] );
$workflow = new Workflows ();
$google_map_api_key = $workflow->read ( GoogleMapClient::APIKEY );
$client = new GoogleMapClient ( $google_map_api_key );
try {
	if (is_geo ( $query )) {
		$params ['latlng'] = $query;
	} else {
		$params ['address'] = $query;
	}
	$result = $client->geocode ( $params );
	if ($result ['status'] != 'OK') {
		$workflow->result ( 'alfredworkflow_404', $result ['status'], $result ['status'], 'No response, try again!', 'icon/google.png' );
	} else {
		foreach ( $result ['results'] as $address ) {
			$address_location = $address ['geometry'] ['location'];
			$latlng = $address_location ['lat'] . ',' . $address_location ['lng'];
			$workflow->result ( $address ['place_id'], $latlng, $address ['formatted_address'], $query, 'icon/google.png' );
		}
	}
} catch ( Exception $e ) {
	$workflow->result ( 'alfredworkflow_500', 'Please make sure your internet works well.', 'No response, try again!', 'Please make sure your internet works well.', 'icon/google.png' );
}

print $workflow->toxml ();