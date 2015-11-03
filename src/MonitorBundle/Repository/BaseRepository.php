<?php

namespace MonitorBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    use BaseRepository\ManageTrait;

    /**
     * @param array $data
     * @return int[]
     */
    protected function convertToScalarArray(array $data)
    {
        return array_map('current', $data);
    }

    /**
     * @param array $data
     * @return int[]
     */
    protected function convertToIntScalarArray(array $data)
    {
        return array_map('intval', $this->convertToScalarArray($data));
    }
}
