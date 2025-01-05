<?php
namespace Insphare\ORM\Doctrine;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\EntityNotFoundException;

class Traversal {

	/**
	 * @var array|object
	 */
	private $data;

	/**
	 * Static call with data and path.
	 *
	 * @param array|object $data
	 * @param string|array $path
	 *
	 * @return mixed
	 */
	public static function traverse($data, $path) {
		$objInstance = new self($data);

		return $objInstance->walk($path);
	}

	/**
	 * Init
	 *
	 * @param array|object $data
	 */
	private function __construct($data) {
		$this->data = $data;
	}

	/**
	 * Walk path. Returns null on wrong way.
	 *
	 * @param string|array $path
	 * @return mixed
	 */
	public function walk($path, $separator = '.') {
		if (is_scalar($path)) {
			$path = explode($separator, $path);
		}
		$mxdData = $this->data;
		try {
			foreach ($path as $strComponent) {
				if (is_array($mxdData)) {
					$mxdData = isset($mxdData[$strComponent]) ? $mxdData[$strComponent] : null;
				}
				elseif (is_object($mxdData)) {
					$strComponent = Inflector::camelize('get_'.$strComponent);
					$mxdData = method_exists($mxdData, $strComponent) ? $mxdData->$strComponent() : null;
				}
				else {
					// error on path
					$mxdData = null;
					break;
				}
			}
		}
		catch (EntityNotFoundException $ex) {
			$mxdData = null;
		}

		return $mxdData;
	}
}
