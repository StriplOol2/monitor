<?php

namespace MonitorBundle\Repository\BaseRepository;

interface ManageTraitInterface
{
    /**
     * @param mixed $entity
     * @return $this
     */
    public function remove($entity);

    /**
     * @param mixed $entity
     * @return $this
     */
    public function merge($entity);

    /**
     * @param mixed $entity
     * @return $this
     */
    public function persist($entity);

    /**
     * @param mixed|null $entity
     * @return $this
     */
    public function flush($entity = null);

    /**
     * @param mixed $entity
     * @return $this
     */
    public function detach($entity);

    /**
     * @param mixed|null $entity
     * @return $this
     */
    public function clear($entity = null);
}
