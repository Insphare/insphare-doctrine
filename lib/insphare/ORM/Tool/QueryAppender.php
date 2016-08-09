<?php
namespace Insphare\ORM\Tool;

use Doctrine\ORM\QueryBuilder;

/**
 * Class QueryAppender
 */
class QueryAppender {

	/**
	 * @var array
	 */
	private $additional = [];

	/**
	 *
	 */
	public function add() {
		$this->additional[] = func_get_args();
	}

	/**
	 * @param QueryBuilder $query
	 */
	public function apply(QueryBuilder $query) {
		foreach ($this->additional as $additional) {
			$method = array_shift($additional);
			call_user_func_array([$query, $method], $additional);
		}
	}

}
