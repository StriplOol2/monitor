<?php

namespace MonitorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToOne(targetEntity="MonitorBundle\Entity\User", inversedBy="searches")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    protected $type;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $activated = false;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="activated_at", type="datetime", nullable=true)
     */
    protected $activatedAt;

    /**
     * @var Advert[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="MonitorBundle\Entity\Advert", mappedBy="search")
     */
    protected $adverts;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        $this->adverts = new ArrayCollection();
    }

    /**
     * @Groups({"default"})
     * @return boolean
     */
    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * @param boolean $activated
     *
     * @return $this
     */
    public function setActivated($activated)
    {
        $this->activated = (bool) $activated;
        if ($this->activated) {
            $this->activatedAt = new \DateTime();
        } else {
            $this->activatedAt = null;
        }

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * @param \DateTime|null $activatedAt
     *
     * @return $this
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;
        return $this;
    }

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

    /**
     * @return null|string
     */
    public function generateUrl()
    {
        if ($this->getUrl()) {
            return $this->getUrl();
        }

        return $this->getUrl();
    }
}
