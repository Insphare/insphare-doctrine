<?php
namespace Insphare\Base\Application;

/**
 * Class Setup
 *
 * @package Insphare\Base
 */
class Setup {

	/**
	 * @var bool
	 */
	private $isRunning = false;
	/**
	 * @var array
	 */
	private $configDirs = array();

	/**
	 *
	 */
	public function __construct() {
		$path = array_slice(explode(DIRECTORY_SEPARATOR, __DIR__), -3);
		$path[] = 'base-config';
		array_push($this->configDirs, implode(DIRECTORY_SEPARATOR, $path));
	}

	/**
	 * Add include path for more config directories and append or overwrite our variables in our known config environment.
	 */
	public function addCustomConfig($configPath) {
		array_push($this->configDirs, (string)$configPath);
	}

	/**
	 *
	 */
	public function run() {
		if (true === $this->isRunning) {
			throw new Exception('The insphare/orm application is already running.');
		}
		var_dump($this->configDirs);
		exit;
		// load config | append other configs | ggf. overwrites
	}
}
