<?php

/**
 * Description of AssetCache_Handler
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 * 
 * @todo setup minifier calls properly via a type-specific minifier class.
 */
class AssetCache_DynamicHandler extends AssetCache_SharedHandler implements AssetCache_Handler {

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
		
		$writeTo = $this->createSingleFileCachePath($originalPath, $this->cachePath);
		
		return $this->$minifier($originalPath, $writeTo);
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
