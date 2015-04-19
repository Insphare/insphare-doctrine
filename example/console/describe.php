<?php
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'exampleBootstrap.php';

$argument = null;
if (!empty($argv[1])) {
	$argument = $argv[1];
}

echo `php doctrine.php orm:mapping:describe $argument`;
