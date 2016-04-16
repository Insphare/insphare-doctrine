<?php
namespace Insphare\ORM\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
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
	public function cqb($offset = null, $limit = null) {
		return $this->createQueryBuilder(Util::removeEntityNamespace($this->getEntityName()))->setFirstResult($offset)->setMaxResults($limit);
	}

	/**
	 * @param QueryBuilder $qb
	 * @param null $custom
	 * @return int
	 */
	public function count(QueryBuilder $qb, $custom = null) {
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
	 *
	 * @return array
	 */
	public function getAll($offset = null, $limit = null, array $order = []) {
		$objQb = $this->cqb($offset, $limit);
		if (!empty($order)) {
			foreach ($order as $column => $sortMode) {
				$objQb->addOrderBy(new Expr\OrderBy($column, $sortMode));
			}
		}

		return $objQb->getQuery()->getResult();
	}

	/**
	 * @since  2015-10
	 *
	 * @return int
	 */
	public function getCountAll() {
		return $this->count($this->cqb());
	}
}
