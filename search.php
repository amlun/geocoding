<?php
require 'workflow.php';
require 'functions.php';
require 'Client.php';

$query = trim ( $argv [1] );
$client = new Google\Maps\Client ();
$workflow = new Workflows ();
try {
	$params = [ 
			'language' => 'zh-CN' 
	];
	if (is_geo ( $query )) {
		$params ['latlng'] = $query;
	} else {
		$params ['address'] = $query;
	}
	$result = $client->geocode ( $params );
	if ($result ['status'] != 'OK') {
		$workflow->result ( 'alfredworkflow_404', $result ['status'], $result ['status'], 'No response, try again!', 'icon.png' );
	} else {
		foreach ( $result ['results'] as $address ) {
			$workflow->result ( $address ['place_id'], $address ['formatted_address'], $address ['formatted_address'], $address ['formatted_address'], 'icon.png' );
		}
	}
} catch ( Exception $e ) {
	$workflow->result ( 'alfredworkflow_500', 'Please make sure your internet works well.', 'No response, try again!', 'Please make sure your internet works well.', 'icon.png' );
}

print $workflow->toxml ();