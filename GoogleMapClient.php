<?php
/**
 * GoogleMapsClient
 *
 * @author lunweiwei
 *        
 */
class GoogleMapClient {
	const END_POINT = 'https://maps.googleapis.com/maps/api/';
	const APIKEY = 'google_map_api_key';
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
}