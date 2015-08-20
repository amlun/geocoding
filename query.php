<?php
require 'workflow.php';
require 'functions.php';
require 'BaiduMapClient.php';

isset ( $argv [1] ) && $query = trim ( $argv [1] );
$workflow = new Workflows ();
$baidu_map_api_key = $workflow->read ( BaiduMapClient::APIKEY );
$place_client = BaiduMapClient::instance ( 'geocoder', $baidu_map_api_key ); // 'EBfee57431c42ec5925b2e9e32c5313b'
try {
	if (is_geo ( $query )) {
		$params ['location'] = $query;
	} else {
		$params ['address'] = $query;
	}
	$result = $place_client->default ( $params );
	if (! $result || $result ['status'] != 0) {
		$workflow->result ( $result ['status'], '找不到地址!', $result ['message'], '找不到相应的地址，请重试!', 'icon/baidu.png' );
	} else {
		$result = $result ['result'];
		if (isset ( $result ['business'] ) && $result ['formatted_address'] && isset ( $result ['addressComponent'] )) {
			$workflow->result ( $query, $result ['business'], $result ['formatted_address'], $query, 'icon/baidu.png' );
			foreach ( $result ['addressComponent'] as $key => $value ) {
				if (empty ( $value ))
					continue;
				$workflow->result ( $key . ':' . $value, $value, $value, $key, 'icon/baidu.png' );
			}
		} elseif (isset ( $result ['location'] )) {
			$latlng = $result ['location'] ['lat'] . ',' . $result ['location'] ['lng'];
			$workflow->result ( $query, $latlng, $latlng, $result ['level'], 'icon/baidu.png' );
		}
	}
} catch ( Exception $e ) {
	$workflow->result ( 'alfredworkflow_500', '程序出错.', '请求失败!', '请求失败!', 'icon/baidu.png' );
}

print $workflow->toxml ();