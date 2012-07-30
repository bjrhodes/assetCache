<?php

/**
 * 
 * @package assetCache
 * @author barry.rhodes@macace.net
 */

interface AssetCache_Handler {

	public function cacheAssets(array $uncompressed, $filetype = null, $returnFullSystemPath = false);
	
	public function setFiletpye($filetype);
	
}
