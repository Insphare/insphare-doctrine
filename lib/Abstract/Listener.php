<?php

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\UnitOfWork;

/**
 * Note: You can use pre/post event listener in annotations too.
 * For more information visit the documentation: http://doctrine-orm.readthedocs.org/en/latest/reference/annotations-reference.html#postpersist
 *
 * Class Abstract_Listener
 */
abstract class Abstract_Listener implements Abstract_Interface {

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
