<?php
namespace Insphare\ORM\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Insphare\ORM\Doctrine\Util;

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
	 * @param null|int $offset
	 * @param null|int $limit
	 * @return QueryBuilder
	 */
	protected function cqb($offset = null, $limit = null) {
		return $this->createQueryBuilder(Util::removeEntityNamespace($this->getEntityName()))->setFirstResult($offset)->setMaxResults($limit);
	}

	/**
	 * @param QueryBuilder $qb
	 * @param null $custom
	 * @return int
	 */
	protected function count(QueryBuilder $qb, $custom = null) {
		$entityName = Util::removeEntityNamespace($this->getEntityName());
		$qb->setFirstResult(null)->setMaxResults(null);
		$qb->select($qb->expr()->count((is_null($custom) ? $entityName : $custom)));
		return (int)$qb->getQuery()->getSingleScalarResult();
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
