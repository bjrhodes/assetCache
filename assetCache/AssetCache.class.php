<?php
/**
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
class AssetCache {
	
	protected $Handler;
	
	/**
	 * includes configs and registers the class autoloader
	 * 
	 * @param string $filetype the type of handler to invoke 
	 * 
	 */
	public function __construct( $filetype = null, $targetFile = null ){
		
		include __DIR__ . '/config.php';

		set_error_handler('AssetCache::errorHandler');
		spl_autoload_register('AssetCache::autoLoader');
		
		if (is_null($targetFile)){
			$this->Handler = new AssetCache_DynamicHandler($filetype);
		} else {
			$this->Handler = new AssetCache_StaticHandler($filetype, $targetFile);
		}
		
	}
	
	public function __call($name, $args){
		if (is_callable(array($this->Handler, $name))){
			return call_user_func_array(array($this->Handler, $name), $args);
		} else {
			throw new Exception('method not found');
		}
	}
	
	/**
	 * @assert ('anything') == null
	 * @depends init
	 * 
	 * @param string $className
	 * @return void 
	 */
	public static function autoLoader($className){
		
			$filename = $className . '.class.php';

			$directories = array(
				ASSETCACHE_ROOT,
				ASSETCACHE_LIB_ROOT
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