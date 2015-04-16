<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\Setup;

/**
 * Class Doctrine_Factory
 */
class Doctrine_Factory {

	/**
	 * @var Doctrine_EntityManager
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
	 * @return Doctrine_EntityManager
	 */
	public static function getEntityManager($real=false) {
		if (null === self::$entityManager) {
			$doctrineFactory = new Doctrine_Factory();
			$em = EntityManager::create($doctrineFactory->getDatabaseParams(), $doctrineFactory->getConfiguration());
			$doctrineFactory->registerEventListener($em);
			self::$entityManager[(int)true] = $em;
			self::$entityManager[(int)false] = new Doctrine_EntityManager($em);
		}

		return self::$entityManager[(int)$real];
	}

	/**
	 * @param EntityManager $em
	 */
	private function registerEventListener(EntityManager $em) {
		$eventManager = $em->getEventManager();
		$events = array(
			Events::onFlush,
			Events::preUpdate,
			Events::preRemove,
			Events::prePersist,
			Events::postUpdate,
			Events::postPersist,
			Events::postRemove,
			Events::postLoad,
		);
		$eventManager->addEventListener($events, new Doctrine_Listener());
		$eventManager->addEventListener($events, new Listener_CaseBased_Meta());
	}

	/**
	 * @return mixed
	 */
	private function getDatabaseParams() {
		$dbParams = Core_Config::get('database-credentials');
		return $dbParams;
	}

	/**
	 * @return \Doctrine\ORM\Configuration
	 */
	private function getConfiguration() {

		$entityDir = Core_Environment::get(Core_Environment::ENTITY_DIR);
		$pathAnnotation = $entityDir . 'entity';
		$pathProxies = $entityDir . 'proxies';
		$isDev = Core_Environment::get(Core_Environment::IS_DEVELOPMENT_MODE);

		$cache = new \Doctrine\Common\Cache\ArrayCache();
		$configuration = Setup::createAnnotationMetadataConfiguration(array($pathAnnotation), $isDev, $pathProxies, $cache, false);
		return $configuration;
	}
}
