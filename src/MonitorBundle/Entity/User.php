<?php

namespace MonitorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use VLru\ApiBundle\Configuration\Serialization\Groups;

/**
 * @ORM\Entity(repositoryClass="MonitorBundle\Repository\UserRepository")
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(columns={"auth_key"})})
 */
class User
{
    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $login;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=300)
     */
    protected $mail;

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
        $this->searches = new ArrayCollection();
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
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     *
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     *
     * @return $this
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
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
