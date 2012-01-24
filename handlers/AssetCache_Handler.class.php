<?php

/**
 * Description of AssetCache_Handler
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
class AssetCache_Handler {

	protected $cachePath;
	
	protected $rawFileRoot;
	
	public function __construct() {
		$this->cachePath = ASSETCACHE_CACHE;
		$this->rawFileRoot = ASSETCACHE_JAVASCRIPT_ROOT;
	}
	
	/**
	 *
	 * @param array $uncompressed the files to cache and minify
	 * @param bool $returnFullSystemPath [optional, default false] set to true 
	 * to retrieve the system path. (Usually you'd want the filename only for a 
	 * web-link)
	 * 
	 * @return string the filename as requested
	 */
	public function cacheAssets(array $uncompressed, $returnFullSystemPath = false){
		
		$cacheFile = $this->createCacheFile($uncompressed);
		
		if($returnFullSystemPath){
			return $cacheFile;
		}
		
		return basename($cacheFile);
	}
	
	
	/**
	 * This currently does nothing but bounce back your filename. Overload it 
	 * for any new handlers
	 * 
	 * @param string $filename full path to file for minification
	 * @return string The cache file created 
	 */
	protected function minifyFile($filename){
		// nope
		return $this->rawFileRoot . $filename;
	}
	
	
	/**
	 * Checks for an existing cache, if one exists, returns its location. 
	 * Otehrwise creates one and returns the location.
	 * 
	 * @param array $uncompressed the files to cache
	 * @return string the path to the new cache file
	 */
	protected function createCacheFile(array $uncompressed){
		
		$cacheFile = $this->createMergedFileCachePath($uncompressed);
		
		if (file_exists($cacheFile)){
			return $cacheFile;
		}
		
		touch($cacheFile);
		$fh = fopen($cacheFile, 'w');
		
		foreach($uncompressed as $file){
			fwrite($fh, file_get_contents($this->minifyFile($file)) );
		}
		
		fclose($fh);
		
		return $cacheFile;
	}
	/**
	 * 
	 * @param string $filename the full path to the original file
	 * @return string the full path to the cache file
	 */
	protected function createSingleFileCachePath($filename){
		return $this->cachePath . md5( $filename . filemtime($filename) );
	}
	
	/**
	 * 
	 * @param array $files 
	 * @return string the full path to the cachefile
	 */
	protected function createMergedFileCachePath(array $files){
		$megaString = '';
		
		foreach ($files as $file){
			if (file_exists($this->rawFileRoot . $file)){
				$megaString .= $file . filemtime($this->rawFileRoot . $file);
			}
		}
		
		return $this->cachePath . md5($megaString);
	}
}
