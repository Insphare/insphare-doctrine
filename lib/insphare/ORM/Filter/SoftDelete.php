<?php
namespace Insphare\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData, Doctrine\ORM\Query\Filter\SQLFilter;

class SoftDelete extends SQLFilter {

	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias) {
		if( $targetEntity->hasField( 'flag_soft_delete' ) ) {
				return $targetTableAlias . '.flag_soft_delete IS NULL';
		}
		return '';
	}
}
