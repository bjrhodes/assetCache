<?php
/**
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
require('../assetCache/AssetCache.class.php');

$cssRequired = array(
	'kickstart.css', 
	'style.css',
	'kickstart-buttons.css',
	'kickstart-menus.css',
	'kickstart-grid.css',
	'jquery.fancybox-1.3.4.css',
	'zellner.css'
	);

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

$Handler = new AssetCache();

$cacheFile = $Handler->cacheAssets(${$arrayName}, $filetype, true);

echo file_get_contents($cacheFile);
