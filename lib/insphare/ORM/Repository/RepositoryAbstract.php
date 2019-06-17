<?php
namespace Insphare\ORM\Repository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Insphare\ORM\Doctrine\Util;
use Insphare\ORM\Tool\QueryAppender;

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
	public function cqb($offset = null, $limit = null) {
		return $this->createQueryBuilder(Util::removeEntityNamespace($this->getEntityName()))->setFirstResult($offset)->setMaxResults($limit);
	}

	/**
	 * @param QueryBuilder $qb
	 * @param null $custom
	 * @return int
	 */
	public function countRows(QueryBuilder $qb, $custom = null) {
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

	/**
	 * @param null|int $offset
	 * @param null|int $limit
	 * @param array $order
	 * @param Criteria $criteria
	 *
	 * @param QueryAppender|null $appender
	 * @return array
	 * @throws \Doctrine\ORM\Query\QueryException
	 */
	public function getAll($offset = null, $limit = null, array $order = [], Criteria $criteria = null, QueryAppender $appender = null) {
		$objQb = $this->cqb($offset, $limit);

		if ($criteria instanceof Criteria) {
			$objQb->addCriteria($criteria);
		}

		if ($appender instanceof QueryAppender) {
			$appender->apply($objQb);
		}

		if (!empty($order)) {
			foreach ($order as $column => $sortMode) {
				$objQb->addOrderBy(new Expr\OrderBy($column, $sortMode));
			}
		}

//		print_r($objQb->getQuery()-> getSQL());

		return $objQb->getQuery()->getResult();
	}

	/**
	 * @since  2015-10
	 *
	 * @param Criteria $criteria
	 *
	 * @return int
	 */
	public function getCountAll($criteria = null) {
		$objQb = $this->cqb();
		if ($criteria instanceof Criteria) {
			$objQb->addCriteria($criteria);
		}

		return $this->countRows($objQb);
	}
}
