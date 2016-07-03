<?php
namespace Insphare\ORM\Tool;

use Insphare\ORM\Doctrine\EntityManager;
use Insphare\ORM\Repository\RepositoryAbstract;

/**
 * Class BulkLoader
 * @package Insphare\ORM\Tool
 */
class BulkLoader {

	/**
	 * @var
	 */
	private $query;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var BulkLoaderInterface
	 */
	private $dataProvider;

	/**
	 * @var int
	 */
	private $offset = 0;

	/**
	 * @param EntityManager $em
	 * @param BulkLoaderInterface $provider
	 */
	public function __construct(EntityManager $em, BulkLoaderInterface $provider) {
		$this->em = $em;
		$this->dataProvider = $provider;
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager() {
		return $this->em;
	}

	/**
	 *
	 */
	public function run() {
		while ($entities = $this->loadEntities()) {
			$this->cliOutput("\rSteps " . (ceil($this->offset / $this->dataProvider->getLimit())) . '/' . (ceil($this->dataProvider->getCount() / $this->dataProvider->getLimit())));
			array_walk($entities, [$this->dataProvider, 'processEntity']);
			$this->getEntityManager()->flush();
			$this->getEntityManager()->clear();
		}
		$this->cliOutput(PHP_EOL, 'FIN', PHP_EOL);
	}

	/**
	 *
	 */
	private function cliOutput() {
		if (PHP_SAPI !== 'cli') {
			return;
		}

		echo implode(' ', func_get_args());
	}

	/**
	 * @return array
	 */
	private function loadEntities() {
		$query = $this->getQuery();
		$query->setFirstResult($this->offset)->setMaxResults($this->dataProvider->getLimit());
		$entities = $query->getResult();
		$this->offset += $this->dataProvider->getLimit();
		return $entities;
	}

	/**
	 * @return \Doctrine\ORM\Query
	 */
	private function getQuery() {
		if (is_null($this->query)) {
			$this->query = $this->dataProvider->getQueryBuilder()->getQuery();
		}

		return $this->query;
	}

}
