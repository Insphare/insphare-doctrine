<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;

class Doctrine_Listener {

	/**
	 * @var Core_ObjectContainer
	 */
	private $objectContainer;

	/**
	 * @param \entity\metaColumns $entity
	 *
	 * @return Abstract_Listener
	 * @throws Exception_ListenerAbstractClassMissing
	 * @throws Exception_ListenerNotAvailable
	 */
	private function getListenerClass(\entity\metaColumns $entity) {
		$entity = get_class($entity);
		Core_Autoloader::treatNameSpace($entity);
		$listenerClassName = 'Listener_' . ucfirst($entity);

		if (null === $this->objectContainer) {
			$this->objectContainer = new Core_ObjectContainer();
		}

		$autoLoader = $this->objectContainer->getCoreAutoloader();
		if (false === $autoLoader->classExists($listenerClassName, true)) {
			throw new Exception_ListenerNotAvailable(get_class($entity));
		}

		$listenerClass = new $listenerClassName();

		if (!$listenerClass instanceof Abstract_Listener) {
			throw new Exception_ListenerAbstractClassMissing(get_class($entity) . ' must implements Abstract_Listener');
		}

		return $listenerClass;
	}

	public function preUpdate(PreUpdateEventArgs $eventArgs) {
		$entity = $eventArgs->getObject();

		try {
			$listenerClass = $this->getListenerClass($entity);
			$listenerClass->preUpdate($entity, $eventArgs);
			foreach ($eventArgs->getEntityChangeSet() as $column => $changeSet) {
				list($before, $after) = $changeSet;
				$listenerClass->preUpdateTrigger($column, $before, $after);
			}
		}
		catch (Exception_ListenerNotAvailable $e) {
		}
	}

	public function preRemove(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	public function postUpdate(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	public function postPersist(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	public function postRemove(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	public function prePersist(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	public function postLoad(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	private function lifecycleEventCall(LifecycleEventArgs $eventArgs, $callback) {
		$callback = explode('::', $callback);
		$callback = end($callback);
		$entity = $eventArgs->getObject();
		try {
			$listenerClass = $this->getListenerClass($entity);
			$listenerClass->{$callback}($entity);
		}
		catch (Exception_ListenerNotAvailable $e) {
		}
	}

	public function onFlush(OnFlushEventArgs $eventArgs) {
		$em = $eventArgs->getEntityManager();
		$uow = $em->getUnitOfWork();

		foreach ($uow->getScheduledEntityInsertions() AS $entity) {
			$this->executeOnFlush($uow->getEntityState($entity), $entity, $uow, $em);
		}

		foreach ($uow->getScheduledEntityUpdates() AS $entity) {
			$this->executeOnFlush($uow->getEntityState($entity), $entity, $uow, $em);
		}

		foreach ($uow->getScheduledEntityDeletions() AS $entity) {
			$this->executeOnFlush($uow->getEntityState($entity), $entity, $uow, $em);
		}

		foreach ($uow->getScheduledCollectionDeletions() AS $col) {
		}

		foreach ($uow->getScheduledCollectionUpdates() AS $col) {
		}
	}

	private function executeOnFlush($state, \entity\metaColumns $entity, UnitOfWork $uow, EntityManager $em) {
		try {
			$skipOn = array(UnitOfWork::STATE_REMOVED, UnitOfWork::STATE_DETACHED);
			if (in_array($uow->getEntityState($entity), $skipOn)) {
				return;
			}
			$listenerClass = $this->getListenerClass($entity);
			$listenerClass->onFlush($entity, $state, $uow);
			$uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);
		}
		catch (Exception_ListenerNotAvailable $e) {
		}
	}
}


