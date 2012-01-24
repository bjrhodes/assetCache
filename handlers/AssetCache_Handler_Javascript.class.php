<?php

/**
 * Description of AssetCache_Handler
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
class AssetCache_Handler_Javascript extends AssetCache_Handler {
	
	public function __construct() {
		$this->cachePath = ASSETCACHE_JAVASCRIPT_ROOT;
		$this->rawFileRoot = ASSETCACHE_JAVASCRIPT_ROOT;
	}
	
	/**
	 * minifies javascript into a cache file and returns the cachefile name
	 * 
	 * @param string $filename full path to file for minification
	 * @return string The cache file created 
	 */
	protected function minifyFile($filename){
		// @todo minify JS
		return $this->rawFileRoot .	$filename;
	}
}
