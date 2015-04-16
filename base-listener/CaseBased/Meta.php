<?php

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\UnitOfWork;

/**
 * Class Listener_CaseBased_Meta
 */
class Listener_CaseBased_Meta {

	/**
	 *
	 */
	const CREATE = 'create';
	/**
	 *
	 */
	const UPDATE = 'update';
	/**
	 *
	 */
	const DELETE = 'softDelete';

	/**
	 * @param PreUpdateEventArgs $eventArgs
	 */
	public function preUpdate(PreUpdateEventArgs $eventArgs) {
		$entity = $eventArgs->getObject();
		foreach ($eventArgs->getEntityChangeSet() as $column => $changeSet) {
			list($before, $after) = $changeSet;
		}
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function preRemove(LifecycleEventArgs $eventArgs) {
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postUpdate(LifecycleEventArgs $eventArgs) {
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postPersist(LifecycleEventArgs $eventArgs) {
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postRemove(LifecycleEventArgs $eventArgs) {
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function prePersist(LifecycleEventArgs $eventArgs) {
	}

	/**
	 * @param LifecycleEventArgs $eventArgs
	 */
	public function postLoad(LifecycleEventArgs $eventArgs) {
	}

	/**
	 * @param OnFlushEventArgs $eventArgs
	 */
	public function onFlush(OnFlushEventArgs $eventArgs) {
		$em = $eventArgs->getEntityManager();
		$uow = $em->getUnitOfWork();
		$compute = new SplObjectStorage();

		foreach ($uow->getScheduledEntityInsertions() AS $entity) {
			$this->setDate($entity, self::CREATE);
			$this->setUser($entity, self::CREATE);
			$this->checkForDeletion($entity);
			$compute->attach($entity);
		}

		foreach ($uow->getScheduledEntityUpdates() AS $entity) {
			$this->setDate($entity, self::UPDATE);
			$this->setUser($entity, self::UPDATE);
			$this->checkForDeletion($entity);
			$compute->attach($entity);
		}

		foreach ($uow->getScheduledEntityDeletions() AS $entity) {
			$this->setDate($entity, self::DELETE);
			$this->setUser($entity, self::DELETE);
			$this->checkForDeletion($entity);
			$compute->attach($entity);
		}

		foreach ($uow->getScheduledCollectionDeletions() AS $col) {

		}

		foreach ($uow->getScheduledCollectionUpdates() AS $col) {
		}

		$skipOn = array(
			UnitOfWork::STATE_REMOVED,
			UnitOfWork::STATE_DETACHED
		);
		foreach (iterator_to_array($compute) as $entity) {
			if (!in_array($uow->getEntityState($entity), $skipOn)) {
				$uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);
			}
		}
	}

	/**
	 * @param \entity\metaColumns $entity
	 */
	private function checkForDeletion(\entity\metaColumns $entity) {
		$flagValue = null;
		if (method_exists($entity, 'getFlagSoftDelete')) {
			$flagValue = $entity->getFlagSoftDelete();
		}

		if (empty($flagValue)) {
			return;
		}

		$this->setDate($entity, self::DELETE);
		$this->setUser($entity, self::DELETE);
	}

	/**
	 * @param \entity\metaColumns $entity
	 * @param $state
	 */
	private function setUser(\entity\metaColumns $entity, $state) {
		$userId = App_Reveal::getIdentity()->getUser()->getId();

		$methodName = $this->generateMethodName($state, 'user');
		if (method_exists($entity, $methodName) && (int)$userId > 0) {
			$entity->{$methodName}($userId);
		}
	}

	/**
	 * @param \entity\metaColumns $entity
	 * @param $state
	 */
	private function setDate(\entity\metaColumns $entity, $state) {
		$methodName = $this->generateMethodName($state, 'date');
		if (method_exists($entity, $methodName)) {
			$date = new DateTime();
			$entity->{$methodName}($date);
		}
	}

	/**
	 * @param $state
	 * @param $prefix
	 * @return string
	 */
	private function generateMethodName($state, $prefix) {
		return lcfirst(implode('', array_map('ucfirst', array(
			'set',
			$prefix,
			$state
		))));
	}
}
