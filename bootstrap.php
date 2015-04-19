<?php

// register our autoloader to support our namespace.
spl_autoload_register(function ($className) {
	$ds = DIRECTORY_SEPARATOR;
	$nsChar = '\\';
	$ns = 'Insphare';
	if (0 === strpos($className, $ns . $nsChar)) {
		$ds = DIRECTORY_SEPARATOR;
		$insphareLibDir = 'lib'.$ds.'insphare'.$ds;
		$arrIncludePath = array($insphareLibDir.'Orm', $insphareLibDir.'Base', 'base-listener', 'base-entity');
		foreach ($arrIncludePath as $directory) {
			$fullPath = implode($ds, array(__DIR__, $directory));
			$file = str_replace(array('\\', $ns), array($ds, $fullPath), $className) . '.php';
			if (file_exists($file)) {
				include_once $file;
			}
		}
	}
});

// register composers autoloader from our dependencies
include_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';


// but should not here... it have to do the customer..
use Insphare\Base\Application\Setup;
$x = new Setup();
$x->run();
