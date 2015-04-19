<?php

// register our autoloader to support our namespace.
//spl_autoload_register(function ($className) {
//	$ds = DIRECTORY_SEPARATOR;
//	$nsChar = '\\';
//	$ns = 'Insphare';
//	if (0 === strpos($className, $ns . $nsChar)) {
//		$insphareLibDir = 'lib'.$ds.'insphare'.$ds;
//		$arrIncludePath = array($insphareLibDir, 'base-listener', 'base-entity');
//		foreach ($arrIncludePath as $directory) {
//			$fullPath = implode($ds, array(__DIR__, $directory));
//			$file = str_replace(array('\\', $ns, $ds.$ds), array($ds, $fullPath, $ds), $className) . '.php';
//			if (file_exists($file)) {
//				include_once $file;
//				break;
//			}
//		}
//	}
//});
$ds = DIRECTORY_SEPARATOR;
include_once implode($ds, array(__DIR__, 'lib', 'insphare', 'Base', 'Autoloader.php'));

$autoloader = new \Insphare\Base\Autoloader();
$autoloader->addIncludePath(__DIR__ . $ds . 'lib' . $ds . 'insphare');
$autoloader->setNameSpace('Insphare');
$autoloader->register();

$autoloader = new \Insphare\Base\Autoloader();
$autoloader->addIncludePath(__DIR__ . $ds . 'base-entity');
$autoloader->setNameSpace('entity');
$autoloader->register();

// register composers autoloader from our dependencies
include_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
