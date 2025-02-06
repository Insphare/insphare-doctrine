<?php
namespace Insphare\ORM\Doctrine;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\Psr6\CacheItem;
use Insphare\Base\EnvironmentVars;
use Insphare\Config\Configuration;
use Insphare\ORM\Doctrine as insphareDoctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Factory
 */
class Factory {

	/**
	 * @var EntityManager
	 */
	private static $entityManager;

	/**
	 *
	 */
	private function __construct() {
		// never use this class as new instance.
	}

	/**
	 * @param bool $real
	 * @return EntityManager
	 */
	public static function getEntityManager($real = false) {
		if (null === self::$entityManager) {
			$doctrineFactory = new Factory();
			$em = EntityManager::create($doctrineFactory->getDatabaseParams(), $doctrineFactory->getConfiguration());
			$doctrineFactory->registerEventListener($em);
			// @todo make this configureable
			$em->getConfiguration()->addFilter('softDelete', 'Insphare\ORM\Filter\SoftDelete');
			$em->getFilters()->enable('softDelete');

			self::$entityManager[(int)true] = $em;
			self::$entityManager[(int)false] = new insphareDoctrine\EntityManager($em);
		}

		return self::$entityManager[(int)$real];
	}

	/**
	 * @param EntityManager $em
	 */
	private function registerEventListener(EntityManager $em) {
		foreach ((array)EnvironmentVars::get('doctrine.eventlistener') as $listenerConfig) {
			$className = (string)$listenerConfig['class'];
			$realClass = new $className();
			$em->getEventManager()->addEventListener((array)$listenerConfig['events'], $realClass);
		}
	}

	/**
	 * @return mixed
	 */
	private function getDatabaseParams() {
		$key = 'database-credentials';
		$dbParams = EnvironmentVars::get($key);
		if (class_exists('Insphare\Config\Configuration') && is_null(Configuration::g($key))) {
			Configuration::c();
			$dbParams = Configuration::g($key);
		}

		return $dbParams;
	}

	/**
	 * @return \Doctrine\ORM\Configuration
	 */
	private function getConfiguration() {
		// isn't required to add the base-entity (meta) into anno's phat! because can be included by extending the class. :)
		$doctrinePhat = EnvironmentVars::get('doctrine.path');
		$arrAnnotations = (array)$doctrinePhat['entities'];
		$pathToProxies = $doctrinePhat['proxy'];
		$isDev = EnvironmentVars::get('doctrine.is_development');
		$configuration = Setup::createAnnotationMetadataConfiguration($arrAnnotations, $isDev, $pathToProxies);

		$path = array_slice(explode(DIRECTORY_SEPARATOR, __DIR__), 0, -6);
		foreach (explode('/', 'beberlei/doctrineextensions/config/mysql.yml') as $pathPart) {
			$path[] = $pathPart;
		}

		$path = implode(DIRECTORY_SEPARATOR, $path);
    $config = Traversal::traverse(Yaml::parse(file_get_contents($path)), 'doctrine.orm.dql');
    foreach ($config as $key => $extensions) {
			switch ($key) {
				case 'datetime_functions':
					$configuration->setCustomDatetimeFunctions($extensions);
					break;

				case 'numeric_functions':
					$configuration->setCustomNumericFunctions($extensions);
					break;

				case 'string_functions':
					$configuration->setCustomStringFunctions($extensions);
					break;
			}
		}

		return $configuration;
	}
}
