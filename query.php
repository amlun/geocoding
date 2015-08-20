<?php
require 'workflow.php';
require 'functions.php';
require 'BaiduMapClient.php';

$query = trim ( $argv [1] );
$workflow = new Workflows ();
$baidu_map_api_key = $workflow->read ( 'baidu_map_api_key' );
$place_client = BaiduMapClient::instance ( 'geocoder', $baidu_map_api_key ); // 'EBfee57431c42ec5925b2e9e32c5313b'
try {
	if (is_geo ( $query )) {
		$params ['location'] = $query;
	} else {
		$params ['address'] = $query;
	}
	$result = $place_client->default ( $params );
	if (! $result || $result ['status'] != 0) {
		$workflow->result ( $result ['status'], 'No match place!', $result ['message'], 'No response, try again!', 'icon.png' );
	} else {
		$result = $result ['result'];
		if (isset ( $result ['business'] ) && $result ['formatted_address'] && isset ( $result ['addressComponent'] )) {
			$workflow->result ( $query, $result ['business'], $result ['formatted_address'], $query, 'icon.png' );
			foreach ( $result ['addressComponent'] as $key => $value ) {
				if (empty ( $value ))
					continue;
				$workflow->result ( $key . ':' . $value, $value, $value, $key, 'icon.png' );
			}
		} elseif (isset ( $result ['location'] )) {
			$workflow->result ( $query, $result ['location'] ['lat'] . ',' . $result ['location'] ['lng'], $result ['location'] ['lat'] . ',' . $result ['location'] ['lng'], $result ['level'], 'icon.png' );
		}
	}
} catch ( Exception $e ) {
	$workflow->result ( 'alfredworkflow_500', 'Please make sure your internet works well.', 'No response, try again!', 'Please make sure your internet works well.', 'icon.png' );
}

print $workflow->toxml ();