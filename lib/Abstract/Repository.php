<?php

/**
 * Class Abstract_Repository
 */
abstract class Abstract_Repository extends \Doctrine\ORM\EntityRepository {

	/**
	 * @return \Doctrine\ORM\Query\Expr
	 */
	protected function expr() {
		return $this->getEntityManager()->getExpressionBuilder();
	}

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	protected function cqb() {
		return $this->createQueryBuilder($this->getEntityName());
	}

	/**
	 * @param $identifier
	 * @return null|object
	 */
	public function findOrGetNewEntity($identifier) {
		$entity = $this->find($identifier);
		if (null === $entity) {
			$entityName = $this->getEntityName();
			$entity = new $entityName;
		}

		return $entity;
	}
}
