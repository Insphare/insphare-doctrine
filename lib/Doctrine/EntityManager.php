<?php
namespace Insphare\Doctrine;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\QueryBuilder;
use entity\metaColumns;

/**
 * Class EntityManager
 */
class EntityManager extends EntityManagerDecorator {

	/**
	 * @param string $entityName
	 *
	 * @return string
	 */
	private function appendDoctrineNameSpace($entityName) {
		return Util::appendDoctrineNameSpace($entityName);
	}

	/**
	 * @param mixed $entity
	 */
	private function iteratorToArray(&$entity) {
		if( $entity instanceof \Traversable ) {
			$entity = iterator_to_array( $entity );
		}
	}

	/**
	 * @param $entities
	 * @throws \InvalidArgumentException
	 */
	private function assertTraversableOrArray($entities) {
		if (!($entities instanceof \Traversable && is_array($entities))) {
			throw new \InvalidArgumentException('$entities have to be an array.');
		}
	}

	/**
	 * @param \entity\metaColumns|object $entity
	 */
	public function persistsAndFlush($entity) {
		$this->wrapped->persist($entity);
		$this->flush($entity);
	}

	/**
	 * @param \entity\metaColumns|object $entity
	 */
	public function removeAndFlush($entity) {
		$this->wrapped->remove($entity);
		$this->flush($entity);
	}

	/**
	 * @param \entity\metaColumns[]|object[] $entities
	 * @param bool $doFlush
	 */
	public function bulkDelete(array $entities, $doFlush = false) {
		$this->assertTraversableOrArray($entities);
		$this->iteratorToArray($entities);
		array_walk($entities, array($this->wrapped, 'remove'));

		if (true === $doFlush) {
			$this->flush($entities);
		}
	}



	/**
	 * @param \entity\metaColumns[]|object[]|\Traversable $entities
	 * @param bool $doFlush
	 *
	 * @throws \InvalidArgumentException
	 */
	public function bulkSoftDelete($entities, $doFlush = false) {
		$this->assertTraversableOrArray($entities);
		$this->iteratorToArray($entities);
		array_walk($entities, array($this, 'softDelete'));

		if (true === $doFlush) {
			$this->flush($entities);
		}
	}

	/**
	 * @param metaColumns $entity
	 * @param bool $doFlush
	 */
	public function softDelete(metaColumns $entity, $doFlush = false) {
		if (!method_exists($entity, Util::buildSetMethodName('set_flag_soft_delete'))) {
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
	public function flush($entity = null) {
		$this->iteratorToArray($entity);
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
		return new QueryBuilder($this->wrapped);
	}

	/**
	 * @param $objectOrArray
	 * @param array|string $path
	 * @return mixed
	 */
	public function traversal($objectOrArray, $path) {
		return Traversal::traverse($objectOrArray, $path);
	}
}
