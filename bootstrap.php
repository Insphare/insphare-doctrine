<?php

// register our autoloader to support our namespace.
spl_autoload_register(function ($className) {
	$ds = DIRECTORY_SEPARATOR;
	$nsChar = '\\';
	$ns = 'insphare';
	if (0 === strpos($className, $ns . $nsChar)) {
		$includePath = implode($ds, array(__DIR__, 'lib'));
		$file = str_replace(array('\\', $ns), array($ds, $includePath), $className) . '.php';
		if (file_exists($file)) {
			include_once $file;
		}
	}
});

// register composers autoloader from our dependencies
include_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
