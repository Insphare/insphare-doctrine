<?php
namespace Insphare\ORM\Listener\Base;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use Insphare\ORM\Doctrine\Util;
use Insphare\ORM\Listener\Exception\ClassMissing;
use Insphare\ORM\Listener\Exception\NotAvailable;
use Insphare\ORM\Listener\ListenerAbstract;

/**
 * Class Entity
 * @package Insphare\Doctrine
 *
 * I'm the "global" entity catcher and all the entities come to me and I offer the entities a storage place with simplified usage. :-)
 */
class Entity {

	/**
	 * @param \entity\metaColumns|object $entity
	 *
	 * @throws \Insphare\ORM\Listener\Exception\NotAvailable
	 * @throws \Insphare\ORM\Listener\Exception\ClassMissing
	 * @return ListenerAbstract
	 */
	private function getListenerClass($entity) {
		$entityClassName = get_class($entity);

		//@todo check
		$entityClassName = Util::removeEntityNamespace($entityClassName);
		$listenerClassName = 'Listener_' . ucfirst($entityClassName);

		if (!class_exists($listenerClassName, true)) {
			throw new NotAvailable($entityClassName);
		}

		$listenerClass = new $listenerClassName();

		if (!$listenerClass instanceof ListenerAbstract) {
			throw new ClassMissing($entityClassName . ' must implements Listener');
		}

		return $listenerClass;
	}

	/**
	 * @param PreUpdateEventArgs $eventArgs
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs) {
		/** @var object $entity */
		$entity = $eventArgs->getObject();

		try {
			$listenerClass = $this->getListenerClass($entity);
			$listenerClass->preUpdate($entity, $eventArgs);
			foreach ($eventArgs->getEntityChangeSet() as $column => $changeSet) {
				list($before, $after) = $changeSet;
				$listenerClass->preUpdateTrigger($column, $before, $after);
			}
		}
		catch (NotAvailable $e) {
		}
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function preRemove(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postUpdate(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postPersist(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postRemove(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function prePersist(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postLoad(LifecycleEventArgs $eventArgs) {
		$this->lifecycleEventCall($eventArgs, __METHOD__);
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 * @param string $callback
	 */
	private function lifecycleEventCall(LifecycleEventArgs $eventArgs, $callback) {
		$callback = explode('::', $callback);
		$callback = end($callback);
		/** @var object $entity */
		$entity = $eventArgs->getObject();
		try {
			$listenerClass = $this->getListenerClass($entity);
			$listenerClass->{$callback}($entity);
		}
		catch (NotAvailable $e) {
		}
	}

	/**
	 * @param OnFlushEventArgs $eventArgs
	 */
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
	}

	/**
	 * @param string $state
	 * @param object $entity
	 * @param UnitOfWork $uow
	 * @param EntityManager $em
	 */
	private function executeOnFlush($state, $entity, UnitOfWork $uow, EntityManager $em) {
		try {
			$skipOn = array(
				UnitOfWork::STATE_REMOVED,
				UnitOfWork::STATE_DETACHED
			);
			if (in_array($uow->getEntityState($entity), $skipOn)) {
				return;
			}
			$listenerClass = $this->getListenerClass($entity);
			$listenerClass->onFlush($entity, $state, $uow);
			$uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);
		}
		catch (NotAvailable $e) {
		}
	}
}


