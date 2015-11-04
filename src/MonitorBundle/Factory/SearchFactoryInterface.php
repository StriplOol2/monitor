<?php

namespace MonitorBundle\Factory;

use MonitorBundle\Entity\User;
use MonitorBundle\Repository\SearchRepositoryInterface;

interface SearchFactoryInterface
{
    /**
     * @param User $user
     * @return mixed
     */
    public function createEmpty(User $user);

    /**
     * @param string $type
     * @return bool
     */
    public function validate($type);

    /**
     * @return SearchRepositoryInterface
     */
    public function getRepository();
}
