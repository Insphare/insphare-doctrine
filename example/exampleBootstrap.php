<?php
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Insphare\Base\Application\Setup;
$setup = new Setup();
$setup->addCustomConfig(__DIR__);
$setup->addEntityPath(__DIR__.DIRECTORY_SEPARATOR.'entities'.DIRECTORY_SEPARATOR.'entity');
$setup->setProxyPath(__DIR__.DIRECTORY_SEPARATOR.'proxies');
$setup->run();
