<?php

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\UnitOfWork;

/**
 * Note: You can use pre/post event listener in annotations too.
 * For more information visit the documentation: http://doctrine-orm.readthedocs.org/en/latest/reference/annotations-reference.html#postpersist
 *
 * Class Abstract_Listener
 */
abstract class Abstract_Listener {

	/**
	 * An entity is in MANAGED state when its persistence is managed by an EntityManager.
	 */
	const STATE_MANAGED = UnitOfWork::STATE_MANAGED;

	/**
	 * An entity is new if it has just been instantiated (i.e. using the "new" operator)
	 * and is not (yet) managed by an EntityManager.
	 */
	const STATE_NEW = UnitOfWork::STATE_NEW;

	/**
	 * The onFlush event occurs after the change-sets of all managed entities are computed. This event is not a lifecycle callback.
	 *
	 * @param \entity\metaColumns $entity
	 * @param int $uowEntityState
	 * @param UnitOfWork $uow
	 * @return void
	 */
	abstract public function onFlush(\entity\metaColumns $entity, $uowEntityState, UnitOfWork $uow);

	/**
	 * Executed before the database UPDATE operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @param PreUpdateEventArgs $eventArgs
	 * @return void
	 */
	abstract public function preUpdate(\entity\metaColumns $entity, PreUpdateEventArgs $eventArgs);

	/**
	 * @param string $columnName
	 * @param string $oldValue
	 * @param string $newValue
	 * @return void
	 */
	abstract public function preUpdateTrigger($columnName, $oldValue, $newValue);

	/**
	 * Executed before the entity manager persist operation is actually executed or cascaded. This call is synchronous with the persist operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	abstract public function prePersist(\entity\metaColumns $entity);

	/**
	 * Executed after the database UPDATE operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	abstract public function postUpdate(\entity\metaColumns $entity);

	/**
	 * Executed after the entity manager persist operation is actually executed or cascaded. This call is invoked after the database INSERT is executed.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	abstract public function postPersist(\entity\metaColumns $entity);

	/**
	 * Executed after the entity manager remove operation is actually executed or cascaded. This call is synchronous with the remove operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	abstract public function postRemove(\entity\metaColumns $entity);

	/**
	 * Executed before the entity manager remove operation is actually executed or cascaded. This call is synchronous with the remove operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	abstract public function preRemove(\entity\metaColumns $entity);

	/**
	 * Executed after an entity has been loaded into the current persistence context or an entity has been refreshed.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	abstract public function postLoad(\entity\metaColumns $entity);

	/**
	 * @param \entity\metaColumns $entity
	 */
	protected function includeInFlush(\entity\metaColumns $entity ) {
		$entityManger = Doctrine_Factory::getEntityManager();

		$objMetaData = $entityManger->getClassMetadata( get_class( $entity ) );
		if( $entityManger->getUnitOfWork()->isScheduledForInsert( $entity ) ||
			$entityManger->getUnitOfWork()->isScheduledForUpdate( $entity ) ||
			$entityManger->getUnitOfWork()->isScheduledForDelete( $entity ) ) {
			$entityManger->getUnitOfWork()->recomputeSingleEntityChangeSet( $objMetaData, $entity );
		}
		else {
			if( $entityManger->getUnitOfWork()->getEntityState( $entity, UnitOfWork::STATE_NEW ) === UnitOfWork::STATE_NEW ) {
				$entityManger->persist( $entity );
			}
			$entityManger->getUnitOfWork()->computeChangeSet( $objMetaData, $entity );
		}
	}
}
