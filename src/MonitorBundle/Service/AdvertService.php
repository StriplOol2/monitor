<?php

namespace MonitorBundle\Service;

use MonitorBundle\Entity\Search;
use MonitorBundle\Entity\User;
use MonitorBundle\Exception\InvalidTypeException;
use MonitorBundle\Exception\SearchNotFoundException;
use MonitorBundle\Exception\UserNotFoundException;
use MonitorBundle\Repository\AdvertRepository;
use MonitorBundle\Repository\SearchRepository;
use MonitorBundle\Repository\UserRepository;
use MonitorBundle\Strategy\CrawlerStrategy;
use Psr\Log\LoggerInterface;
use MonitorBundle\Adapter\CrawlerAdapterInterface;

class AdvertService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var AdvertRepository
     */
    protected $advertRepository;

    /**
     * @var SearchRepository
     */
    protected $searchRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CrawlerStrategy
     */
    protected $crawlerStrategy;

    /**
     * AdvertService constructor.
     * @param UserRepository $userRepository
     * @param AdvertRepository $advertRepository
     * @param SearchRepository $searchRepository
     * @param CrawlerStrategy $crawlerStrategy
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $userRepository,
        AdvertRepository $advertRepository,
        SearchRepository $searchRepository,
        CrawlerStrategy $crawlerStrategy,
        LoggerInterface $logger
    ) {
        $this->userRepository = $userRepository;
        $this->advertRepository = $advertRepository;
        $this->searchRepository = $searchRepository;
        $this->logger = $logger;
        $this->crawlerStrategy = $crawlerStrategy;
    }

    /**
     * @param string $authKey
     * @param int $searchId
     * @param int $lastUpdateTimestamp
     * @return \MonitorBundle\Entity\Advert[]
     */
    public function getAdverts($authKey, $searchId, $lastUpdateTimestamp)
    {
        $user = $this->getUser($authKey);
        $search = $this->getSearch($searchId, $user->getId());
        $lastUpdateDateTime = new \DateTime();
        $lastUpdateDateTime->setTimestamp($lastUpdateTimestamp);
        return $this->advertRepository->findByUserSearchLastUpdate(
            $search->getId(),
            $lastUpdateDateTime
        );
    }

    /**
     * @param int $searchId
     * @return bool
     */
    public function generateLastAdverts($searchId)
    {
        $search = $this->searchRepository->find($searchId);
        if (null === $search) {
            $this->logger->error('Cannot get search', [
               'search_id' => $searchId
            ]);
            return false;
        }
        /** @var Search $search */
        if (null === $search->generateUrl()) {
            $this->logger->error('Url is null', [
                'search_id' => $searchId,
                'url' => $search->generateUrl()
            ]);
            return false;
        }
        $crawler = $this->getCrawler($searchId, $search);
        if (null === $crawler) {
            return false;
        }

        $urls = $this->getAdvertUrls($searchId, $search, $crawler);

        if (!is_array($urls)) {
            $this->logger->error('Cannot get urls adverts', [
                'search_id' => $searchId,
                'url' => $search->generateUrl()
            ]);
            return false;
        }

        foreach ($urls as $url) {
            $advert = $this->getAdvert($url, $crawler);
            if ($advert !== null) {
                $advert->setSearch($search);
                if (null === $this->advertRepository->findOneBy([
                            'search' => $search->getId(),
                            'hash' => $advert->getHash()
                ])) {
                    $this->advertRepository->persist($advert);
                }
            }
            usleep(100000);
        }

        $this->advertRepository->flush();
        return true;
    }

    /**
     * @param string $url
     * @param CrawlerAdapterInterface $crawler
     * @return \MonitorBundle\Entity\Advert|null
     */
    protected function getAdvert($url, CrawlerAdapterInterface $crawler)
    {
        $advert = null;
        try {
            $advert = $crawler->getAdvert($url);
        } catch (\Exception $e) {
            $this->logger->error('Cannot get advert', [
                'url' => $url,
                'exception' => $e
            ]);
        }

        return $advert;
    }

    /**
     * @param $searchId
     * @param Search $search
     * @return null|CrawlerAdapterInterface
     */
    protected function getCrawler($searchId, Search $search)
    {
        try {
            $crawlerFactory = $this->crawlerStrategy->getFactory($search->getType());
        } catch (InvalidTypeException $e) {
            $this->logger->error('Cannot get factory crawler', [
                'search_id' => $searchId,
                'exception' => $e
            ]);
            return null;
        }

        return $crawlerFactory->create();
    }

    /**
     * @param $searchId
     * @param Search $search
     * @param CrawlerAdapterInterface $crawler
     * @return bool|\string[]
     */
    protected function getAdvertUrls($searchId, Search $search, CrawlerAdapterInterface $crawler)
    {
        $urls = false;

        try {
            $urls = $crawler->getAdvertUrls($search->generateUrl());
        } catch (\Exception $e) {
            $this->logger->error('Cannot get urls adverts', [
                'search_id' => $searchId,
                'url' => $search->generateUrl()
            ]);
        }

        return $urls;
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
}
