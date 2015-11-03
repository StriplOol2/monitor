<?php

namespace MonitorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MonitorBundle\Repository\DromSearchRepository")
 * @ORM\Table(name="search_drom")
 */
class DromSearch extends AbstractSearch
{

}
