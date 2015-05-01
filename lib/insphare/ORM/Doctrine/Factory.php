<?php
namespace Insphare\ORM\Doctrine;

use Doctrine\Common\Cache\ArrayCache;
use Insphare\Base\EnvironmentVars;
use Insphare\ORM\Doctrine as insphareDoctrine;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

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
		$dbParams = EnvironmentVars::get('database-credentials');
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
		$cache = new ArrayCache();
		$configuration = Setup::createAnnotationMetadataConfiguration($arrAnnotations, $isDev, $pathToProxies, $cache, false);
		return $configuration;
	}
}
