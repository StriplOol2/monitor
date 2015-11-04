<?php

namespace VLru\ApiBundle\AnnotationLoader;

use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;

class ApiAnnotationDirectoryLoader extends AnnotationDirectoryLoader
{
    /**
     * @param mixed $resource
     * @param null $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return $type === 'api';
    }
}
