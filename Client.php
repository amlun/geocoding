<?php

namespace Google\Maps;

/**
 * GoogleMapsClient
 *
 * @author lunweiwei
 *        
 */
class Client {
	const END_POINT = 'https://maps.googleapis.com/maps/api/';
	const DEFAULT_LANG = 'zh-CN';
	private $_key = null;
	/**
	 * set api key
	 *
	 * @param string $api_key        	
	 */
	public function __construct($api_key = null) {
		$this->_key = $api_key;
	}
	/**
	 * Geocoding (Latitude/Longitude Lookup) / $params['address'] = 'Beijing'
	 * Reverse Geocoding (Address Lookup) / $params['latlng'] = '40.714224,-73.961452'
	 *
	 * @param array $params        	
	 * @return Ambigous <mixed, \Google\Maps\Ambigous, boolean>
	 */
	public function geocode($params) {
		$data = $this->_get ( 'geocode/json', $params );
		if ($data) {
			$data = json_decode ( $data, true );
			return $data;
		}
		return false;
	}
	/**
	 * client get data from remote
	 *
	 * @param string $uri        	
	 * @param array $params        	
	 * @return Ambigous <boolean, mixed>
	 */
	public function _get($uri, $params = array()) {
		! empty ( $this->_key ) && $params ['key'] = $this->_key;
		isset ( $params ['language'] ) or $params ['language'] = self::DEFAULT_LANG;
		$url = self::END_POINT . $uri . '?' . http_build_query ( $params );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)' );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 5 );
		$data = curl_exec ( $ch );
		$httpcode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		curl_close ( $ch );
		return ($httpcode >= 200 && $httpcode < 300) ? $data : false;
	}
	/**
	 * Validates latitude
	 *
	 * @param mixed $latitude        	
	 *
	 * @return bool
	 */
	public function isValidLatitude($latitude) {
		return $this->isNumericInBounds ( $latitude, - 90.0, 90.0 );
	}
	
	/**
	 * Validates longitude
	 *
	 * @param mixed $longitude        	
	 *
	 * @return bool
	 */
	public function isValidLongitude($longitude) {
		return $this->isNumericInBounds ( $longitude, - 180.0, 180.0 );
	}
	
	/**
	 * Checks if the given value is (1) numeric, and (2) between lower
	 * and upper bounds (including the bounds values).
	 *
	 * @param float $value        	
	 * @param float $lower        	
	 * @param float $upper        	
	 *
	 * @return bool
	 */
	public function isNumericInBounds($value, $lower, $upper) {
		if (! is_numeric ( $value )) {
			return false;
		}
		
		if ($value < $lower || $value > $upper) {
			return false;
		}
		
		return true;
	}
}