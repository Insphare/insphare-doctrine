<?php
namespace Insphare\Base;

//@todo add method to set include path
//@todo add none namespace usage

/**
 * Class Autoloader
 * @package Insphare\Base
 */
class Autoloader {

	/**
	 *
	 */
	const DS = DIRECTORY_SEPARATOR;
	/**
	 *
	 */
	const NS_CHAR = '\\';

	/**
	 * @var null
	 */
	private $nameSpace = null;

	/**
	 * @param null $nameSpace
	 */
	public function setNameSpace($nameSpace) {
		$this->nameSpace = $nameSpace;
	}

	/**
	 *
	 */
	public function register() {
		spl_autoload_register(array(
			$this,
			'autoload'
		));
	}

	/**
	 *
	 */
	public function unregister() {
		spl_autoload_unregister(array(
			$this,
			'autoload'
		));
	}

	/**
	 * @param $className
	 */
	private function autoload($className) {
		if (0 === strpos($className, $this->nameSpace . self::NS_CHAR)) {
			$libDir = 'lib' . self::DS . 'insphare' . self::DS;
			$arrIncludePath = array(
				$libDir,
				'base-listener',
				'base-entity'
			);
			foreach ($arrIncludePath as $directory) {
				$fullPath = __DIR__ . self::DS . $directory;

				$file = str_replace(array(
						'\\',
						$this->nameSpace,
						self::DS . self::DS
					), array(
						self::DS,
						$fullPath,
						self::DS
					), $className) . '.php';

				if (file_exists($file)) {
					include_once $file;
					break;
				}
			}
		}
	}
}
