<?php
/**
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
class AssetCache {
	
	/**
	 * includes configs and registers the class autoloader
	 * 
	 * @param none
	 * @return object filetype handler 
	 * 
	 * @codeCoverageIgnore - PHPUnit uses a different bootstrap
	 */
	public static function init($filetype = null){
			
		include __DIR__ . '/config.php';

		set_error_handler('AssetCache::errorHandler');
		spl_autoload_register('AssetCache::myLoader');
		
		$className = 'AssetCache_Handler_' . ucfirst($filetype);
		if (class_exists($className)){
			return new $className();
		} else {
			return new AssetCache_Handler();
		}
		
	}
	
	/**
	 * @assert ('anything') == null
	 * @depends init
	 * 
	 * @param string $className
	 * @return void 
	 */
	public static function myLoader($className){
		
			$filename = $className . '.class.php';

			$directories = array(
				ASSETCACHE_ROOT,
				ASSETCACHE_HANDLER_ROOT
			);
			
			foreach ($directories as $dir){
				$fullPath = $dir . $filename;
				if (file_exists($fullPath)){
					require_once ($fullPath);
					return;
				}
			}
			
		}
	
	/**
	 *
	 * @param type $errno
	 * @param type $errstr
	 * @param type $errfile
	 * @param type $errline 
	 * 
	 * @codeCoverageIgnore - phpunit has its own error handler
	 */
	public static function errorHandler($errno, $errstr, $errfile, $errline ) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

}