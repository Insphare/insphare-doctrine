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

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return ExampleUser
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return ExampleUser
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return ExampleUser
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return ExampleUser
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set initials
     *
     * @param string $initials
     *
     * @return ExampleUser
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * Get initials
     *
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
    }

    /**
     * Set loginCount
     *
     * @param integer $loginCount
     *
     * @return ExampleUser
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount
     *
     * @return integer
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Set userCreate
     *
     * @param integer $userCreate
     *
     * @return ExampleUser
     */
    public function setUserCreate($userCreate)
    {
        $this->user_create = $userCreate;

        return $this;
    }

    /**
     * Get userCreate
     *
     * @return integer
     */
    public function getUserCreate()
    {
        return $this->user_create;
    }

    /**
     * Set userUpdate
     *
     * @param integer $userUpdate
     *
     * @return ExampleUser
     */
    public function setUserUpdate($userUpdate)
    {
        $this->user_update = $userUpdate;

        return $this;
    }

    /**
     * Get userUpdate
     *
     * @return integer
     */
    public function getUserUpdate()
    {
        return $this->user_update;
    }

    /**
     * Set userSoftDelete
     *
     * @param integer $userSoftDelete
     *
     * @return ExampleUser
     */
    public function setUserSoftDelete($userSoftDelete)
    {
        $this->user_soft_delete = $userSoftDelete;

        return $this;
    }

    /**
     * Get userSoftDelete
     *
     * @return integer
     */
    public function getUserSoftDelete()
    {
        return $this->user_soft_delete;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return ExampleUser
     */
    public function setDateCreate($dateCreate)
    {
        $this->date_create = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return ExampleUser
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->date_update = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * Set dateSoftDelete
     *
     * @param \DateTime $dateSoftDelete
     *
     * @return ExampleUser
     */
    public function setDateSoftDelete($dateSoftDelete)
    {
        $this->date_soft_delete = $dateSoftDelete;

        return $this;
    }

    /**
     * Get dateSoftDelete
     *
     * @return \DateTime
     */
    public function getDateSoftDelete()
    {
        return $this->date_soft_delete;
    }

    /**
     * Set flagSoftDelete
     *
     * @param integer $flagSoftDelete
     *
     * @return ExampleUser
     */
    public function setFlagSoftDelete($flagSoftDelete)
    {
        $this->flag_soft_delete = $flagSoftDelete;

        return $this;
    }

    /**
     * Get flagSoftDelete
     *
     * @return integer
     */
    public function getFlagSoftDelete()
    {
        return $this->flag_soft_delete;
    }
}
