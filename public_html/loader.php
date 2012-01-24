<?php
/**
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
require('../AssetCache.class.php');

$cssRequired = array('kickstart.css', 'style.css');
$javascriptRequired = array('jquery.snippet.min.js', 'kickstart.js');

switch ( strtolower( filter_input(INPUT_GET, 'type') ) ) {
	
	case 'css':
		$filetype = 'css';
		header('Content-type: text/css');
		break;
	
	default:
		$filetype = 'javascript';
		header('Content-type: text/javascript');
		break;

}

$arrayName = $filetype . 'Required';

$Handler = AssetCache::init($filetype);
echo file_get_contents($Handler->cacheAssets(${$arrayName}, true));
?>