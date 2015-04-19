<?php
namespace Insphare\Base\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\UnitOfWork;

interface ListenerInterface {

	/**
	 * The onFlush event occurs after the change-sets of all managed entities are computed. This event is not a lifecycle callback.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @param int $uowEntityState
	 * @param UnitOfWork $uow
	 * @return void
	 */
	public function onFlush($entity, $uowEntityState, UnitOfWork $uow);

	/**
	 * Executed before the database UPDATE operation.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @param PreUpdateEventArgs $eventArgs
	 * @return void
	 */
	public function preUpdate($entity, PreUpdateEventArgs $eventArgs);

	/**
	 * @param string $columnName
	 * @param string $oldValue
	 * @param string $newValue
	 * @return void
	 */
	public function preUpdateTrigger($columnName, $oldValue, $newValue);

	/**
	 * Executed before the entity manager persist operation is actually executed or cascaded. This call is synchronous with the persist operation.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @return void
	 */
	public function prePersist($entity);

	/**
	 * Executed after the database UPDATE operation.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @return void
	 */
	public function postUpdate($entity);

	/**
	 * Executed after the entity manager persist operation is actually executed or cascaded. This call is invoked after the database INSERT is executed.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @return void
	 */
	public function postPersist($entity);

	/**
	 * Executed after the entity manager remove operation is actually executed or cascaded. This call is synchronous with the remove operation.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @return void
	 */
	public function postRemove($entity);

	/**
	 * Executed before the entity manager remove operation is actually executed or cascaded. This call is synchronous with the remove operation.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @return void
	 */
	public function preRemove($entity);

	/**
	 * Executed after an entity has been loaded into the current persistence context or an entity has been refreshed.
	 *
	 * @param \entity\metaColumns|object $entity
	 * @return void
	 */
	public function postLoad($entity);
}
