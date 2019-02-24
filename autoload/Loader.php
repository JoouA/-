<?php
class Loader
{
	/*映射路径*/
	public static $vendorMap = [
		'App' => __DIR__ . DIRECTORY_SEPARATOR . 'app'
	];

	/*自动加载器*/
	public static function autoload($class)
	{	
		 $file_path = self::findFile($class);
		 self::inludeFile($file_path);
	}

	/*解析文件路径*/
	private static function findFile($class)
	{
		// 找到一级命名空间
		$vendor = substr($class, 0, strpos($class, '\\'));

		//一级命名空间对应的文件路径	
		$vendorDir = self::$vendorMap[$vendor];

		// 获取一级命令空间后面的路径
		$fille_path = substr($class, strlen($vendor)) . '.php';

	    return  str_replace(DIRECTORY_SEPARATOR, '/', $vendorDir .  $fille_path);
	}

	private static function inludeFile($file)
	{
		if (is_file($file)) {
			include $file;
		}
	}
}