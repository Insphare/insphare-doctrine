<?php

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\UnitOfWork;

class Listener_User extends Abstract_Listener {

	/**
	 * The onFlush event occurs after the change-sets of all managed entities are computed. This event is not a lifecycle callback.
	 *
	 * @param \entity\metaColumns $entity
	 * @param int $uowEntityState
	 * @param UnitOfWork $uow
	 * @return void
	 */
	public function onFlush(\entity\metaColumns $entity, $uowEntityState, UnitOfWork $uow) {
		// TODO: Implement onFlush() method.
	}

	/**
	 * Executed before the database UPDATE operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @param PreUpdateEventArgs $eventArgs
	 * @return void
	 */
	public function preUpdate(\entity\metaColumns $entity, PreUpdateEventArgs $eventArgs) {
		// TODO: Implement preUpdate() method.
	}

	/**
	 * @param string $columnName
	 * @param string $oldValue
	 * @param string $newValue
	 * @return void
	 */
	public function preUpdateTrigger($columnName, $oldValue, $newValue) {
		// TODO: Implement preUpdateTrigger() method.
	}

	/**
	 * Executed before the entity manager persist operation is actually executed or cascaded. This call is synchronous with the persist operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	public function prePersist(\entity\metaColumns $entity) {
		// TODO: Implement prePersist() method.
	}

	/**
	 * Executed after the database UPDATE operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	public function postUpdate(\entity\metaColumns $entity) {
		// TODO: Implement postUpdate() method.
	}

	/**
	 * Executed after the entity manager persist operation is actually executed or cascaded. This call is invoked after the database INSERT is executed.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	public function postPersist(\entity\metaColumns $entity) {
		// TODO: Implement postPersist() method.
	}

	/**
	 * Executed after the entity manager remove operation is actually executed or cascaded. This call is synchronous with the remove operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	public function postRemove(\entity\metaColumns $entity) {
		// TODO: Implement postRemove() method.
	}

	/**
	 * Executed before the entity manager remove operation is actually executed or cascaded. This call is synchronous with the remove operation.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	public function preRemove(\entity\metaColumns $entity) {
		// TODO: Implement preRemove() method.
	}

	/**
	 * Executed after an entity has been loaded into the current persistence context or an entity has been refreshed.
	 *
	 * @param \entity\metaColumns $entity
	 * @return void
	 */
	public function postLoad(\entity\metaColumns $entity) {
		// TODO: Implement postLoad() method.
	}
}
