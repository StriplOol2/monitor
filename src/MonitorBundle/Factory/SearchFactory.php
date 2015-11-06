<?php

namespace MonitorBundle\Factory;

use MonitorBundle\Entity\Search;

class SearchFactory
{
    /**
     * @param $user
     * @param $type
     * @return Search
     */
    public function createEmpty($user, $type)
    {
        return (new Search())->setUser($user)->setType($type);
    }
}
