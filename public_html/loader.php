<?php
/**
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
require('../assetCache/AssetCache.class.php');

$cssRequired = array(
	'chosen.css',
	'kickstart.css', 
	'style.css',
	'kickstart-buttons.css',
	'kickstart-forms.css',
	'kickstart-grid.css',
	'kickstart-menus.css',
	'kickstart-icons.min.css',
	'prettify.css',
	'tiptip.css',
	'jquery.fancybox-1.3.4.css',
	'zellner.css'
	);

$javascriptRequired = array('prettify.js','kickstart.js');

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
