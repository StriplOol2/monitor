<?php

namespace VLru\ApiBundle\AnnotationLoader;

use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Routing\Route;
use VLru\ApiBundle\Configuration\Route\Version;

class ApiAnnotationLoader extends AnnotatedRouteControllerLoader
{
    /**
     * Configures the _controller default parameter and eventually the HTTP method
     * requirement of a given Route instance.
     *
     * @param Route             $route  A route instance
     * @param \ReflectionClass  $class  A ReflectionClass instance
     * @param \ReflectionMethod $method A ReflectionClass method
     * @param mixed             $annot  The annotation class instance
     *
     * @throws \LogicException When the service option is specified on a method
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        parent::configureRoute($route, $class, $method, $annot);

        // requirements (@Version)
        foreach ($this->reader->getMethodAnnotations($method) as $version) {
            if ($version instanceof Version) {
                $route->setPath('/{version_major}.{version_minor}'.$route->getPath());
                $route->addRequirements(['version_major' => '\d+', 'version_minor' => '\d+']);

                $conditions = [];
                $majorVersion = "pathVariable('version_major')";
                $minorVersion = "pathVariable('version_minor')";

                if ($version->getMajorFrom() === $version->getMajorTo()) {
                    $conditions[] = $majorVersion.' == '.$version->getMajorFrom();

                    if ($version->getMinorFrom() === $version->getMinorTo()) {
                        $conditions[] = $minorVersion.' == '.$version->getMinorFrom();
                    } else {
                        if (null !== $version->getMinorFrom()) {
                            $conditions[] = $minorVersion.' >= '.$version->getMinorFrom();
                        }

                        if (null !== $version->getMinorTo()) {
                            $conditions[] = $minorVersion.' <= '.$version->getMinorTo();
                        }
                    }
                } else {
                    if (null !== $version->getMajorFrom()) {
                        $conditions[] =
                            '(('.$majorVersion.' > ' .$version->getMajorFrom().') or ('.
                            $majorVersion.' == '.$version->getMajorFrom().' and '.
                            $minorVersion.' >= '.$version->getMinorFrom().'))';
                    }

                    if (null !== $version->getMajorTo()) {
                        $conditions[] =
                            '(('.$majorVersion.' < ' .$version->getMajorTo().') or ('.
                            $majorVersion.' == '.$version->getMajorTo().' and '.
                            $minorVersion.' <= '.$version->getMinorTo().'))';
                    }
                }

                $route->setCondition(\join(' and ', $conditions));
            }
        }
    }

    public function supports($resource, $type = null)
    {
        return $type === 'api';
    }
}
