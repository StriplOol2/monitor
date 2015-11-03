<?php

namespace MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractSearch
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
}
