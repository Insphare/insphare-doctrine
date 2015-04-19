<?php
namespace Insphare\Doctrine;
use Doctrine\ORM\QueryBuilder as originQueryBuilder;

/**
 * Class EntityManager
 */
class QueryBuilder extends originQueryBuilder {

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
		$from = Util::appendDoctrineNameSpace($from);

		return parent::from($from, $alias, $indexBy);
	}

}
