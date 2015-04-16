<?php
namespace entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\UnitOfWork;

/**
 * @ORM\Table(name="metaColumns", *indexes={@ORM\Index(name="ix_flag_soft_delete", columns={"flag_soft_delete"})})
 * @ORM\MappedSuperclass
 */
abstract class metaColumns {

	/**
	 * @ORM\Column(type="bigint", nullable=true, options={"default":NULL, "unsigned"=true})
	 */
	protected $user_create;

	/**
	 * @ORM\Column(type="bigint", nullable=true, options={"default":NULL, "unsigned"=true})
	 */
	protected $user_update;

	/**
	 * @ORM\Column(type="bigint", nullable=true, options={"default":NULL, "unsigned"=true})
	 */
	protected $user_soft_delete;

	/**
	 * @ORM\Column(type="datetime", nullable=true, options={"default":NULL})
	 */
	protected $date_create;

	/**
	 * @ORM\Column(type="datetime", nullable=true, options={"default":NULL})
	 */
	protected $date_update;

	/**
	 * @ORM\Column(type="datetime", nullable=true, options={"default":NULL})
	 */
	protected $date_soft_delete;

	/**
	 * @ORM\Column(type="smallint", length=1, nullable=true, options={"default":NULL})
	 */
	protected $flag_soft_delete;

	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	private function getEntityManager() {
		return \Doctrine_Factory::getEntityManager();
	}

	public function isStateNew() {
		return $this->getEntityManager()->getUnitOfWork()->getEntityState($this) === UnitOfWork::STATE_NEW;
	}

	public function getCurrentDate() {
		return new \DateTime();
	}

}
