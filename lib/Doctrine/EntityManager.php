<?php

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class Doctrine_EntityManager
 */
class Doctrine_EntityManager extends EntityManagerDecorator {

	/**
	 * @param string $entityName
	 *
	 * @return string
	 */
	private function appendDoctrineNameSpace($entityName) {
		return Doctrine_Util::appendDoctrineNameSpace($entityName);
	}



	/**
	 * @param mixed $entity
	 */
	private function treatTraversable(&$entity) {
		if( $entity instanceof \Traversable ) {
			$entity = iterator_to_array( $entity );
		}
	}

	private function assertTraversableOrArray($entities) {
		if (!($entities instanceof Traversable && is_array($entities))) {
			throw new InvalidArgumentException('$entities have to be an array.');
		}
	}

	/**
	 * @param \entity\metaColumns $entity
	 */
	public function persistsAndFlush(\entity\metaColumns $entity) {
		$this->wrapped->persist($entity);
		$this->flush($entity);
	}

	/**
	 * @param \entity\metaColumns $entity
	 */
	public function removeAndFlush(\entity\metaColumns $entity) {
		$this->wrapped->remove($entity);
		$this->flush($entity);
	}

	/**
	 * @param \entity\metaColumns[] $entities
	 * @param bool $doFlush
	 */
	public function bulkDelete(array $entities, $doFlush = false) {
		$this->assertTraversableOrArray($entities);
		$this->treatTraversable($entities);
		array_walk($entities, array($this->wrapped, 'remove'));

		if (true === $doFlush) {
			$this->flush($entities);
		}
	}



	/**
	 * @param \entity\metaColumns[]|Traversable $entities
	 * @param bool $doFlush
	 *
	 * @throws InvalidArgumentException
	 */
	public function bulkSoftDelete($entities, $doFlush = false) {
		$this->assertTraversableOrArray($entities);
		$this->treatTraversable($entities);
		array_walk($entities, array($this, 'softDelete'));

		if (true === $doFlush) {
			$this->flush($entities);
		}
	}

	/**
	 * @param \entity\metaColumns $entity
	 * @param bool $doFlush
	 */
	public function softDelete(\entity\metaColumns $entity, $doFlush = false) {
		if (!method_exists($entity, Doctrine_Util::buildSetMethodName('set_flag_soft_delete'))) {
			return;
		}

		$entity->setFlagSoftDelete(1);
		$this->wrapped->persist($entity);

		if (true === $doFlush) {
			$this->flush($entity);
		}
	}



	/**
	* @param $entity
	 */
	public function flush($entity) {
		$this->treatTraversable($entity);
		$this->wrapped->flush($entity);
	}

	/**
	 * @param string $className
	 *
	 * @return \Doctrine\Common\Persistence\ObjectRepository
	 */
	public function getRepository($className) {
		return $this->wrapped->getRepository($this->appendDoctrineNameSpace($className));
	}

	/**
	 * @param string $entityName
	 * @param mixed $id
	 *
	 * @return object
	 */
	public function getReference($entityName, $id) {
		return $this->wrapped->getReference($this->appendDoctrineNameSpace($entityName), $id);
	}

	/**
	 * @param string $entityName
	 * @param mixed $id
	 * @param int $lockMode
	 * @param null $lockVersion
	 *
	 * @return object
	 */
	public function find($entityName, $id, $lockMode = null, $lockVersion = null) {
		return $this->wrapped->find($this->appendDoctrineNameSpace($entityName), $id, $lockMode, $lockVersion);
	}

	/**
	 * @param string $className
	 * @return \Doctrine\Common\Persistence\Mapping\ClassMetadata
	 */
	public function getClassMetadata($className) {
		return $this->wrapped->getClassMetadata($this->appendDoctrineNameSpace($className));
	}

	/**
	 * @return QueryBuilder
	 */
	public function createQueryBuilder() {
		return new Doctrine_QueryBuilder($this->wrapped);
	}

	/**
	 * @param $objectOrArray
	 * @param array|string $path
	 * @return mixed
	 */
	public function traversal($objectOrArray, $path) {
		return Entity_Traversal::traverse($objectOrArray, $path);
	}
}
