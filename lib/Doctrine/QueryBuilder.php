<?php
use Doctrine\ORM\QueryBuilder;

/**
 * Class Doctrine_EntityManager
 */
class Doctrine_QueryBuilder extends QueryBuilder {

	/**
	 * Support auto-namespace.
	 *
	 * @param string $from The class name.
	 * @param string $alias The alias of the class.
	 * @param string $indexBy The index for the from.
	 *
	 * @return QueryBuilder This QueryBuilder instance.
	 */
	public function from($from, $alias, $indexBy = null) {
		$from = Doctrine_Util::appendDoctrineNameSpace($from);

		return parent::from($from, $alias, $indexBy);
	}

}
