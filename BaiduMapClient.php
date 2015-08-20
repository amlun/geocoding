<?php
/**
 * BaiduMapClient
 *
 * @author lunweiwei
 *        
 */
class BaiduMapClient {
	const END_POINT = 'http://api.map.baidu.com/';
	const VERSION = 'v2';
	const APIKEY = 'baidu_map_api_key';
	private static $_services = [ 
			'place',
			'geocoder' 
	];
	private static $_key;
	private static $_instances = null;
	private $_service;
	public static function instance($service, $key = null) {
		if (! isset ( self::$_instances [$service] )) {
			if (! in_array ( $service, self::$_services )) {
				throw new InvalidArgumentException ( 'Service: ' . $service . ' not exists!' );
			}
			self::$_instances [$service] = new static ( $service );
		}
		self::$_key = $key;
		return self::$_instances [$service];
	}
	/**
	 * set api key
	 *
	 * @param string $api_key        	
	 */
	public function __construct($service_name = null) {
		$this->_service = $service_name;
	}
	/**
	 * 请求百度接口
	 *
	 * @param string $method        	
	 * @param array $params        	
	 * @return BaiduMapClient
	 */
	public function __call($method = 'default', $params = null) {
		$params = isset ( $params [0] ) ? $params [0] : array ();
		! empty ( self::$_key ) && $params ['ak'] = self::$_key;
		if ('default' == $method) {
			$method = '';
		}
		$params ['output'] = 'json';
		$url = self::END_POINT . $this->_service . '/' . self::VERSION . '/' . $method . '?' . http_build_query ( $params );
		$data = $this->_request ( $url );
		if ($data) {
			$data = json_decode ( $data, true );
			return $data;
		}
		return false;
	}
	
	/**
	 * HTTP请求
	 *
	 * @param string $url        	
	 * @return Ambigous <boolean, mixed>
	 */
	private function _request($url) {
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