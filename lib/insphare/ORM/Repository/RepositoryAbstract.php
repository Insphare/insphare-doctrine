<?php
namespace Insphare\ORM\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class RepositoryAbstract
 */
abstract class RepositoryAbstract extends EntityRepository {

	/**
	 * @return \Doctrine\ORM\Query\Expr
	 */
	protected function expr() {
		return $this->getEntityManager()->getExpressionBuilder();
	}

	/**
	 * @return QueryBuilder
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
