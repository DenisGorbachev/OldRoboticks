<?php

require_once dirname(__FILE__).'/../yaml/sfYaml.php';

class Config {
	public static $config = array();
	
	public static function load($dir) {
		self::$config = array();
		foreach (scandir($dir) as $file) {
			if ($file[0] == '.') {
				continue;
			}
			self::$config[pathinfo($file, PATHINFO_FILENAME)] = sfYaml::load($dir.'/'.$file);
		}
	} 
	
	public static function get($keyString, $default = null) {
		$keys = explode('/', $keyString);
		$array = self::$config;
		foreach ($keys as $key) {
			if (!array_key_exists($key, $array)) {
				return $default;
			} else {
				$array = $array[$key];
			}
		}
		return $array;
	}
	
}	