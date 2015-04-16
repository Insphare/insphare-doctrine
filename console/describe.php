<?php
include_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$argument = null;
if (!empty($argv[1])) {
	$argument = $argv[1];
}

echo `php doctrine.php orm:mapping:describe $argument`;
