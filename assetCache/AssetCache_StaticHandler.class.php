<?php

/**
 * 
 * @package assetCache
 * @author barry.rhodes@macace.net
 */

class AssetCache_StaticHandler extends AssetCache_SharedHandler implements AssetCache_Handler {
	
	protected $rawFileRoot;
	
	protected $targetFile;
	
	protected $extension;
	
	protected $filetype;
	
	/**
	 *
	 * @param type $filetype 
	 */
	public function __construct( $filetype = null, $targetFile ) {
		
		$this->setFiletpye($filetype);
		$this->targetFile = $targetFile;
		$this->cachePath = ASSETCACHE_CACHE;
	}
	
	
	
	protected function createCacheFile(array $uncompressed){
		
		touch($this->targetFile);
		
		$fh = fopen($this->targetFile, 'w');
		
		foreach($uncompressed as $file){
			fwrite($fh, file_get_contents($this->minifyFile($file)) );
		}
		
		fclose($fh);
		
		return $this->targetFile;
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
				
		$extensions = array_reverse(explode('.', $filename));
		
		// skip files already minified
		if ( isset($extensions[1]) && $extensions[1] == 'min'){
			return $filename;
		}
		
		$writeTo = $this->createSingleFileCachePath($filename);
		
		return $this->$minifier($filename, $writeTo);
	}
}
