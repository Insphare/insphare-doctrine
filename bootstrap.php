<?php

// register our autoloader to support our namespace.
spl_autoload_register(function ($className) {
	$ds = DIRECTORY_SEPARATOR;
	$nsChar = '\\';
	$ns = 'insphare';
	if (0 === strpos($className, $ns . $nsChar)) {
		$file = str_replace('\\', $ds, $className) . '.php';
		$file = str_replace('insphare', implode($ds, array(__DIR__, 'lib')), $file);
		if (file_exists($file)) {
			include_once $file;
		}
	}
});

// register composers autoloader from our dependencies
include_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
