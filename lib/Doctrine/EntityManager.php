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
	 * @param \entity\metaColumns $entity
	 */
	public function persistsAndFlush(\entity\metaColumns $entity) {
		$this->wrapped->persist($entity);
		$this->wrapped->flush($entity);
	}

	/**
	 * @param \entity\metaColumns $entity
	 */
	public function removeAndFlush(\entity\metaColumns $entity) {
		$this->wrapped->remove($entity);
		$this->wrapped->flush($entity);
	}

	/**
	 * @param \entity\metaColumns[] $entities
	 * @param bool $doFlush
	 */
	public function bulkDelete(array $entities, $doFlush = false) {
		foreach ($entities as $entity) {
			$this->wrapped->remove($entity);

			if (true === $doFlush) {
				$this->wrapped->flush($entity);
			}
		}
	}

	/**
	 * @param \entity\metaColumns[] $entities
	 * @param bool $doFlush
	 */
	public function bulkSoftDelete(array $entities, $doFlush = false) {
		foreach ($entities as $entity) {
			$this->softDelete($entity, $doFlush);
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
			$this->wrapped->flush($entity);
		}
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
