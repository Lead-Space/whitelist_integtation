<?php

namespace WhiteList;

class Config
{
	public static array $params = [];

	public static function get($key)
	{
		self::$params = json_decode(file_get_contents("../config.json"), true);
		return self::$params[$key] ?? false;
	}

//	public static function set($key,$value): void {
//		self::$params[$key] = $value;
//		file_put_contents("../config.json", json_encode(self::$params));
//	}


//	public function __get($key)
//	{
//		return self::get($key);
//	}

	public function __isset($key)
	{
		self::$params = json_decode(file_get_contents("../config.json"), true);
		return isset(self::$params[$key]);
	}
}