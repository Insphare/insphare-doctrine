<?php

/**
 * Doctrine_Util
 *
 * Global custom made doctrine Helper functions.
 *
 */
class Doctrine_Util {

	/**
	 * @param string|array $methodName
	 * @return string
	 */
	public static function buildSetMethodName($methodName) {
		return self::buildMethodName($methodName, 'set');
	}

	/**
	 * @param string|array $methodName
	 * @return string
	 */
	public static function buildGetMethodName($methodName) {
		return self::buildMethodName($methodName, 'get');
	}

	/**
	 * @param string|array $methodName
	 * @param string $mode
	 * @return string
	 */
	private static function buildMethodName($methodName, $mode) {
		if (is_string($methodName)) {
			$methodName = explode('_', $methodName);
		}

		$search = array_search($mode, $methodName);
		if (false !== $search && $search === 0) {
			unset($methodName[$search]);
		}
		return lcfirst(implode('', array_map('ucfirst', array_merge(array($mode), $methodName))));
	}

	/**
	 * @param string $entityName
	 *
	 * @return string
	 */
	public static function appendDoctrineNameSpace($entityName) {
		$nameSpace = Core_Config::get('doctrine.namespace');
		if (empty($nameSpace)) {
			return $entityName;
		}

		self::ensureBackSlashAtFirstChar($nameSpace);
		self::ensureBackSlashAtFirstChar($entityName);

		if (0 !== strpos($entityName, $nameSpace)) {
			$entityName = preg_replace('~' . preg_quote(PHP_BACKSLASH) . '{2,}~', PHP_BACKSLASH, $nameSpace . $entityName);
		}

		return ltrim($entityName, '\\');
	}

	/**
	 * @param $string
	 */
	private static function ensureBackSlashAtFirstChar(&$string) {
		if (PHP_BACKSLASH === substr($string, 0, 1)) {
			$string = ltrim($string, PHP_BACKSLASH);
		}
	}
}
