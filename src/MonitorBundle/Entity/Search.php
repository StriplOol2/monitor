<?php

namespace MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VLru\ApiBundle\Configuration\Serialization\Groups;

/**
 * @ORM\Entity(repositoryClass="MonitorBundle\Repository\SearchRepository")
 * @ORM\Table(name="search")
 */
class Search
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
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    protected $url;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="MonitorBundle\Entity\User", inversedBy="dromSearches")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    protected $type;

    /**
     * @Groups({"default"})
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @Groups({"default"})
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
     * @Groups({"default"})
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url = null)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
}
