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
use Psr\Log\LoggerInterface;

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
     * @var int
     */
    protected $searchTimeoutSeconds;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * SearchService constructor.
     * @param SearchRepository $searchRepository
     * @param UserRepository $userRepository
     * @param LoggerInterface $logger
     * @param $searchTimeoutSeconds
     */
    public function __construct(
        SearchRepository $searchRepository,
        UserRepository $userRepository,
        LoggerInterface $logger,
        $searchTimeoutSeconds
    ) {
        $this->searchRepository = $searchRepository;
        $this->userRepository = $userRepository;
        $this->searchFactory = new SearchFactory();
        $this->searchTimeoutSeconds = $searchTimeoutSeconds;
        $this->logger = $logger;
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
        $search = $this->getSearch($searchId, $user->getId());
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

        return $this->searchRepository->findBy(['user' => $user->getId()]);
    }

    /**
     * @param Search $searchUpdated
     * @param string $authKey
     * @return bool
     */
    public function updateSearch(Search $searchUpdated, $authKey)
    {
        $user = $this->getUser($authKey);
        $search = $this->getSearch($searchUpdated->getId(), $user->getId());
        $search
            ->setType($searchUpdated->getType())
            ->setUrl($searchUpdated->getUrl())
            ->setActivated($searchUpdated->isActivated());

        $this->searchRepository->merge($search);
        $this->searchRepository->flush($search);

        return true;
    }

    /**
     * @param string $authKey
     * @return User|null
     */
    protected function getUser($authKey)
    {
        $user = $this->userRepository->findOneBy(['authKey' => $authKey]);
        if (null === $user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /**
     * @param int $searchId
     * @param int $userId
     * @return Search|null
     */
    protected function getSearch($searchId, $userId)
    {
        $search = $this->searchRepository->findOneBy(['id' => $searchId, 'user' => $userId]);
        if (null === $search) {
            throw new SearchNotFoundException();
        }
        return $search;
    }

    public function runActualActiveSearches()
    {
        $dateTime = new \DateTime();
        $timestamp = $dateTime->getTimestamp();
        $timestamp -= $this->searchTimeoutSeconds;
        $dateTime->setTimestamp($timestamp);
        $searches = $this->searchRepository->findActualActivated($dateTime);
        foreach ($searches as $search) {
            $this->logger->info('start exec');
            echo "php /var/www/monitor/app/console m:a:a --search_id {$search->getId()} > /dev/null 2>&1";
            exec("php /var/www/monitor/app/console m:a:a --search_id {$search->getId()} > /dev/null 2>&1");
            $this->logger->info('stop exec');
        }

    }
}
