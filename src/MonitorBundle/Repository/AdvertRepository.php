<?php

namespace MonitorBundle\Repository;

use MonitorBundle\Entity\Advert;

class AdvertRepository extends BaseRepository
{
    /**
     * @param $searchId
     * @param \DateTime $lastUpdateDateTime
     * @return Advert[]
     */
    public function findByUserSearchLastUpdate($searchId, \DateTime $lastUpdateDateTime)
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.search = :searchId')
            ->andWhere('a.createAt >= :timestamp')
            ->setParameter('timestamp', $lastUpdateDateTime)
            ->setParameter('searchId', $searchId)
            ->orderBy('a.id', 'DESC');

        return $qb->getQuery()
            ->getResult();
    }
}
