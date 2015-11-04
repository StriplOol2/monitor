<?php

namespace MonitorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MonitorBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
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
     * @var DromSearch[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="MonitorBundle\Entity\DromSearch", mappedBy="user")
     */
    protected $dromSearches;
}
