<?php
namespace entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExampleUser
 *
 * @ORM\Entity(
 *		repositoryClass="\Repository_User"
 * )
 * @ORM\Table(
 *		name="example_user"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class ExampleUser extends metaColumns {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id = null;

	/**
	 * @ORM\Column(type="string", length=32, unique=true, nullable=false)
	 */
	protected $userName;

	/**
	 * @ORM\Column(type="string", length=3)
	 */
	protected $country;

	/**
	 * @ORM\Column(type="decimal", precision=2, scale=1, nullable=true)
	 */
	protected $height;

	/**
	 * @ORM\Column(type="decimal", precision=2, scale=1, nullable=true)
	 */
	protected $weight;

	/**
	 * @ORM\Column(type="string", length=2, options={"fixed":true, "comment":"Initial letters of first and last name"})
	 */
	protected $initials;

	/**
	 * @ORM\Column(type="integer", name="login_count", nullable=false, options={"unsigned":true, "default":0})
	 */
	protected $loginCount;
}
