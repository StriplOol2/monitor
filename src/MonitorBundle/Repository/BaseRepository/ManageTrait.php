<?php

namespace MonitorBundle\Repository\BaseRepository;

/**
 * @method \Doctrine\ORM\EntityManager getEntityManager
 */
trait ManageTrait
{
    /**
     * @param mixed $entity
     * @return $this
     */
    public function remove($entity)
    {
        $this->getEntityManager()
            ->remove($entity);

        return $this;
    }

    /**
     * @param mixed $entity
     * @return $this
     */
    public function merge($entity)
    {
        $this->getEntityManager()
            ->merge($entity);

        return $this;
    }

    /**
     * @param mixed $entity
     * @return $this
     */
    public function persist($entity)
    {
        $this->getEntityManager()
            ->persist($entity);

        return $this;
    }

    /**
     * @param mixed|null $entity
     * @return $this
     */
    public function flush($entity = null)
    {
        $this->getEntityManager()
            ->flush($entity);

        return $this;
    }

    /**
     * @param mixed $entity
     * @return $this
     */
    public function detach($entity)
    {
        $this->getEntityManager()
            ->detach($entity);

        return $this;
    }

    /**
     * @param mixed|null $entity
     * @return $this
     */
    public function clear($entity = null)
    {
        $this->getEntityManager()
            ->clear($entity);

        return $this;
    }
}
