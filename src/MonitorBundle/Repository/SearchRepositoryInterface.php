<?php

namespace MonitorBundle\Repository;

interface SearchRepositoryInterface extends
    \MonitorBundle\Repository\BaseRepository\ManageTraitInterface,
    \Doctrine\Common\Persistence\ObjectRepository,
    \Doctrine\Common\Collections\Selectable
{

}
