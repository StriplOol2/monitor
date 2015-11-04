<?php

namespace MonitorBundle\Factory;

use MonitorBundle\Entity\DromSearch;
use MonitorBundle\Entity\User;
use MonitorBundle\Repository\DromSearchRepository;
use MonitorBundle\Repository\SearchRepositoryInterface;

class DromSearchFactory implements SearchFactoryInterface
{
    /**
     * @var DromSearchRepository
     */
    protected $repository;

    /**
     * DromSearchFactory constructor.
     * @param DromSearchRepository $repository
     */
    public function __construct(DromSearchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function createEmpty(User $user)
    {
        return (new DromSearch())->setUser($user);
    }

    /**
     * @return SearchRepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function validate($type)
    {
        return 'drom' === $type;
    }
}
