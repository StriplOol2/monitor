<?php

namespace MonitorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use VLru\ApiBundle\Configuration\Serialization\Groups;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="MonitorBundle\Repository\UserRepository")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(columns={"auth_key"})})
 */
class User extends BaseUser
{
    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Search[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="MonitorBundle\Entity\Search", mappedBy="user")
     */
    protected $searches;

    /**
     * @Groups({"default"})
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $authKey;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->searches = new ArrayCollection();
        $this->authKey = $this->getUsername();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ArrayCollection|Search[]
     */
    public function getSearches()
    {
        return $this->searches;
    }

    /**
     * @param ArrayCollection|Search[] $searches
     *
     * @return $this
     */
    public function setSearches($searches)
    {
        $this->searches = $searches;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param string $authKey
     *
     * @return $this
     */
    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
        return $this;
    }
}
