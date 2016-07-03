<?php
namespace Insphare\ORM\Tool;

use Doctrine\ORM\QueryBuilder;

interface BulkLoaderInterface {

	/**
	 * @return int
	 */
	public function getLimit();

	/**
	 * @return string
	 */
	public function getEntityName();

	/**
	 * @return QueryBuilder
	 */
	public function getQueryBuilder();

	/**
	 * @param object $entity
	 */
	public function processEntity($entity);

	/**
	 * @return int
	 */
	public function getCount();

}
