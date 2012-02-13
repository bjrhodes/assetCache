<?php

/**
 * Description of AssetCache_Handler
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 * 
 * @todo setup minifier calls properly via a type-specific minifier class.
 */
class AssetCache_Handler {

	protected $cachePath;
	
	protected $rawFileRoot;
	
	protected $webPath;
	
	protected $extension;
	
	protected $filetype;
	
	
	public function __construct( $filetype = null ) {
		
		$this->cachePath = ASSETCACHE_CACHE;
		$this->setFiletpye($filetype);
		
	}
	
	/**
	 *
	 * @param array $uncompressed the files to cache and minify
	 * @param string $filetype [optional] js or css to over-ride defailt filetype.
	 * @param bool $returnFullSystemPath [optional, default false] set to true 
	 * to retrieve the system path. (Usually you'd want the filename only for a 
	 * web-link)
	 * 
	 * @return string the filename as requested
	 */
	public function cacheAssets(array $uncompressed, $filetype = null, $returnFullSystemPath = false){
		
		if (!is_null($filetype)){
			$this->setFiletpye($filetype);
		}
		
		$cacheFile = $this->createCacheFile($uncompressed);
		
		if($returnFullSystemPath){
			return $cacheFile;
		}
		
		return basename($cacheFile);
	}
	
	
	public function setFiletpye($filetype){
		
		switch ( strtolower($filetype) ) {
			case 'css':
				$this->webPath = ASSETCACHE_CSS_WEBROOT;
				$this->rawFileRoot = ASSETCACHE_CSS_ROOT;
				$this->extension = '.css';
				$this->filetype ='css';
				break;
			default:
				$this->webPath = ASSETCACHE_JAVASCRIPT_WEBROOT;
				$this->rawFileRoot = ASSETCACHE_JAVASCRIPT_ROOT;
				$this->extension = '.js';
				$this->filetype ='javascript';
				break;
		}
		
	}
	
	/**
	 * This currently does nothing but bounce back your filename. Overload it 
	 * for any new handlers
	 * 
	 * @param string $filename full path to file for minification
	 * @return string The cache file created 
	 */
	protected function minifyFile($filename){
		
		$minifier = 'minify' . ucfirst($this->filetype);
		
		$originalPath = $this->rawFileRoot . $filename;
		
		$extensions = array_reverse(explode('.', $filename));
		
		// skip files already minified
		if ( isset($extensions[1]) && $extensions[1] == 'min'){
			return $originalPath;
		}
		
		$writeTo = $this->createSingleFileCachePath($originalPath);
		
		return $this->$minifier($originalPath, $writeTo);
	}
	
	
	protected function minifyCss($original, $writeTo){
		
		file_put_contents($writeTo, CssMin::minify(file_get_contents($original)));
		
		return $writeTo;
	}
	
	protected function minifyJavascript($original, $writeTo){
		// @todo
		file_put_contents($writeTo, JSMin::minify(file_get_contents($original)));
		
		return $writeTo;
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
		return $this->cachePath . md5( $filename . filemtime($filename) ) . '.min' . $this->extension;
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
		
		return $this->webPath . md5($megaString) . '.min' . $this->extension;
	}
}
