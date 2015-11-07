<?php

namespace MonitorBundle\Repository;

use MonitorBundle\Entity\Search;

class SearchRepository extends BaseRepository
{
    /**
     * @param \DateTime $dateTime
     * @return Search[]
     */
    public function findActualActivated(\DateTime $dateTime)
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.activated = 1')
            ->andWhere('s.activatedAt >= :timestamp')
            ->setParameter('timestamp', $dateTime);

        return $qb->getQuery()
            ->getResult();
    }
}
