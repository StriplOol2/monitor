<?php

namespace MonitorBundle\Service;

use MonitorBundle\Entity\User;
use MonitorBundle\Exception\UserNotFoundException;
use MonitorBundle\Repository\UserRepository;
use MonitorBundle\Strategy\SearchStrategy;

class SearchService
{
    /**
     * @var SearchStrategy
     */
    protected $searchStrategy;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * SearchService constructor.
     * @param SearchStrategy $searchStrategy
     * @param UserRepository $userRepository
     */
    public function __construct(
        SearchStrategy $searchStrategy,
        UserRepository $userRepository
    ) {
        $this->searchStrategy = $searchStrategy;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $type
     * @param int $userId
     * @return bool
     */
    public function createSearchByType($type, $userId)
    {
        $user = $this->getUser($userId);
        if (null === $user) {
            return false;
        }

        $factory = $this->searchStrategy->getFactory($type);
        if (null === $factory) {
            return false;
        }
        $searchRepository = $factory->getRepository();

        $search = $factory->createEmpty($user);
        $searchRepository->persist($search);
        $searchRepository->flush($search);

        return true;
    }

    /**
     * @param int $userId
     * @return array
     * @throws UserNotFoundException
     */
    public function getSearches($userId)
    {
        $result = [];

        $user = $this->getUser($userId);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        foreach ($this->searchStrategy->getFactories() as $factory) {
            $result += $factory->getRepository()->findBy(['user' => $userId]);
        }

        return $result;
    }

    /**
     * @param $userId
     * @return null|User
     */
    protected function getUser($userId)
    {
        return $this->userRepository->find($userId);
    }
}
