<?php
/**
 *
 * @package assetCache
 * @author bjrhodes199@hotmail.com
 */
define ('ASSETCACHE_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define ('ASSETCACHE_LIB_ROOT', ASSETCACHE_ROOT . 'lib/');

// uncompressed resources
define ('ASSETCACHE_JAVASCRIPT_ROOT', ASSETCACHE_ROOT . '../javascript/');
define ('ASSETCACHE_CSS_ROOT', ASSETCACHE_ROOT . '../stylesheets/');

// individually minified file cache
define ('ASSETCACHE_CACHE', ASSETCACHE_ROOT . '../cache/');

// final output folders
define ('ASSETCACHE_JAVASCRIPT_WEBROOT', ASSETCACHE_ROOT . '../public_html/javascript/');
define ('ASSETCACHE_CSS_WEBROOT', ASSETCACHE_ROOT . '../public_html/css/');
