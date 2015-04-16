<?php

class Repository_User extends Abstract_Repository {

	public function test($a,$b) {
		$objQb = $this->cqb();
		$objQb->andWhere($this->expr()->eq('user.id', 1));

		$d = $objQb->getQuery()->getSQL();
		$d=1;
	}
}
