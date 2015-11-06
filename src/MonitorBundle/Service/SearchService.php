<?php

namespace MonitorBundle\Service;

use MonitorBundle\Entity\Search;
use MonitorBundle\Entity\User;
use MonitorBundle\Exception\InvalidTypeException;
use MonitorBundle\Exception\SearchNotFoundException;
use MonitorBundle\Exception\UserNotFoundException;
use MonitorBundle\Factory\SearchFactory;
use MonitorBundle\Repository\SearchRepository;
use MonitorBundle\Repository\UserRepository;

class SearchService
{
    /**
     * @var SearchRepository
     */
    protected $searchRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var SearchFactory
     */
    protected $searchFactory;

    /**
     * SearchService constructor.
     * @param SearchRepository $searchRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        SearchRepository $searchRepository,
        UserRepository $userRepository
    ) {
        $this->searchRepository = $searchRepository;
        $this->userRepository = $userRepository;
        $this->searchFactory = new SearchFactory();
    }

    /**
     * @param Search $search
     * @param string $authKey
     * @return bool
     * @throws UserNotFoundException
     * @throws InvalidTypeException
     */
    public function createSearch(Search $search, $authKey)
    {
        $user = $this->getUser($authKey);

        if (null === $user) {
            throw new UserNotFoundException();
        }
        $search->setUser($user);
        $this->searchRepository->persist($search);
        $this->searchRepository->flush($search);

        return true;
    }

    /**
     * @param $authKey
     * @param $searchId
     * @return bool
     * @throws InvalidTypeException
     * @throws UserNotFoundException
     * @throws SearchNotFoundException
     */
    public function deleteSearch($authKey, $searchId)
    {
        $user = $this->getUser($authKey);
        if (null === $user) {
            throw new UserNotFoundException();
        }

        $search = $this->searchRepository->find($searchId);
        if (null === $search) {
            throw new SearchNotFoundException();
        }

        $this->searchRepository->remove($search);
        $this->searchRepository->flush($search);

        return true;
    }

    /**
     * @param string $authKey
     * @return array
     * @throws UserNotFoundException
     */
    public function getSearches($authKey)
    {
        $user = $this->getUser($authKey);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $this->searchRepository->findBy(['user' => $user->getId()]);
    }

    /**
     * @param $authKey
     * @return User|null
     */
    protected function getUser($authKey)
    {
        return $this->userRepository->findOneBy(['authKey' => $authKey]);
    }

    /**
     * @param Search $searchUpdated
     * @param string $authKey
     * @return bool
     */
    public function updateSearch(Search $searchUpdated, $authKey)
    {
        $user = $this->getUser($authKey);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $search = $this->searchRepository->find($searchUpdated->getId());
        if (null === $search) {
            throw new SearchNotFoundException();
        }
        /** @var Search $search */
        $search
            ->setType($searchUpdated->getType())
            ->setUrl($searchUpdated->getUrl())
            ->setActivated($searchUpdated->isActivated());

        $this->searchRepository->merge($search);
        $this->searchRepository->flush($search);

        return true;
    }
}
