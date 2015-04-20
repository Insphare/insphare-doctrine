<?php

include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'exampleBootstrap.php';

$doctrinePath = \Insphare\Base\EnvironmentVars::get('doctrine.path');
$entityPath = reset($doctrinePath['entities']);
$proxyPath = $doctrinePath['proxy'];
$commands = array(
	'orm:clear-cache:metadata',
	'orm:clear-cache:query',
	'orm:clear-cache:result',
	'orm:generate-entities ' . $entityPath,
	'orm:generate-proxies ' . $proxyPath,
);

foreach ($commands as $command) {
	echo `php doctrine.php $command`;
}
