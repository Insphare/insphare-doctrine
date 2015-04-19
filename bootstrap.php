<?php

$ds = DIRECTORY_SEPARATOR;
include_once implode($ds, array(__DIR__, 'lib', 'insphare', 'Base', 'Autoloader.php'));

$arrIncludePath = array(
	'Insphare' => __DIR__ . $ds . 'lib' . $ds . 'insphare',
	'entity' => __DIR__ . $ds . 'base-entity',
);

foreach ($arrIncludePath as $namespace => $includePath) {
	$autoloader = new \Insphare\Base\Autoloader();
	$autoloader->addIncludePath($includePath);
	$autoloader->setNameSpace($namespace);
	$autoloader->register();
}

// register composers autoloader from our dependencies
include_once 'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
