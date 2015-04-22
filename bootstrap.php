<?php

$ds = DIRECTORY_SEPARATOR;

$arrIncludePath = array(
	'entity' => __DIR__ . $ds . 'base-entity' . $ds . 'entity',
	'Insphare' => __DIR__ . $ds . 'lib' . $ds . 'insphare',
);

foreach ($arrIncludePath as $namespace => $includePath) {
	$autoloader = new \Insphare\Base\Autoloader();
	$autoloader->addIncludePath($includePath);
	$autoloader->setNameSpace($namespace);
	$autoloader->register();
}

use Insphare\Base\ObjectContainer;
use Insphare\Base\Application\Setup;

$object = new ObjectContainer();
/** @var Setup $setup */
$setup = $object->getSetup();
$setup->addCustomConfig(__DIR__.DIRECTORY_SEPARATOR.'base-config');
