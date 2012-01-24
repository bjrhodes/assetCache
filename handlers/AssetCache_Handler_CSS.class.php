<?php

/**
 * Description of AssetCache_Handler
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
class AssetCache_Handler_Css extends AssetCache_Handler {

	public function __construct() {
		$this->cachePath = ASSETCACHE_CSS_ROOT;
		$this->rawFileRoot = ASSETCACHE_CSS_ROOT;
	}
	
	/**
	 * Minifies the css file specified into a cache file and returns the cache 
	 * filename
	 * 
	 * @param string $filename full path to file for minification
	 * @return string The cache file created 
	 */
	protected function minifyFile($filename){
		// @todo minify css
		return $this->rawFileRoot . $filename;
	}
	
}
