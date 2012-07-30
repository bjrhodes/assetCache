<?php
/**
 * 
 * @package assetCache
 * @author barry.rhodes@macace.net
 */

class AssetCache_SharedHandler {
	
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
	 * 
	 * @param string $filename the full path to the original file
	 * @return string the full path to the cache file
	 */
	protected function createSingleFileCachePath($filename){
		return $this->cachePath . md5( $filename . filemtime($filename) ) . '.min' . $this->extension;
	}
	
	
}
