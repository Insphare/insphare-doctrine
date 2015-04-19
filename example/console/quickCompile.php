<?php

include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'exampleBootstrap.php';

$entityPath = \Insphare\Base\EnvironmentVars::get('doctrine.path');
$entityPath = $entityPath['entities'];
var_dump($entityPath);exit;
$commands = array(
	'orm:clear-cache:metadata',
	'orm:clear-cache:query',
	'orm:clear-cache:result',
	'orm:generate-entities ' . $entityPath,
	'orm:generate-proxies ' . $entityPath . DIRECTORY_SEPARATOR . 'proxies',
);

foreach ($commands as $command) {
	echo `php doctrine.php $command`;
}
