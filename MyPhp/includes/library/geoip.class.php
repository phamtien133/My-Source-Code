<?php
	require_once DIR_LIB . 'ip' . DS . 'Reader.php';
	require_once DIR_LIB . 'ip' . DS . 'Reader'. DS .'Decoder.php';
	require_once DIR_LIB . 'ip' . DS . 'Reader'. DS .'InvalidDatabaseException.php';
	require_once DIR_LIB . 'ip' . DS . 'Reader'. DS .'Logger.php';
	require_once DIR_LIB . 'ip' . DS . 'Reader'. DS .'Metadata.php';
	
	use MaxMind\Db\Reader;
class geoip
{
	private $ip = '';
	public function __construct($ip) {
		$this->ip = $ip;
	}
	public function get()
	{
		// GeoLite2-City
		$reader = new Reader(DIR_LIB . 'ip' . DS . 'db.mmdb');
		
		$t = $reader->get($this->ip);
		
		return $t;
	}
}