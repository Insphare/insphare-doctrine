<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$entityPath = dirname(Registry::get(Registry::appDir)) . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR;
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
