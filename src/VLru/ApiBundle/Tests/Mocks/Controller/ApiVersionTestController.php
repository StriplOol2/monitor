<?php

namespace VLru\ApiBundle\Tests\Mocks\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VLru\ApiBundle\Configuration\Route\Version;
use VLru\ApiBundle\Controller\BaseApiController;

/**
 * @Route(service="api.version_test.controller")
 */
class ApiVersionTestController extends BaseApiController
{
    /**
     * @Route("/api-version-test")
     * @Method({"GET"})
     * @Version(to="1.5")
     */
    public function action1()
    {
        return $this->createSuccessApiJsonResponse('action1');
    }

    /**
     * @Route("/api-version-test")
     * @Method({"GET"})
     * @Version(from="1.6", to="2.5")
     */
    public function action2()
    {
        return $this->createSuccessApiJsonResponse('action2');
    }

    /**
     * @Route("/api-version-test")
     * @Method({"GET"})
     * @Version(from="2.6", to="2.6")
     */
    public function action3()
    {
        return $this->createSuccessApiJsonResponse('action3');
    }

    /**
     * @Route("/api-version-test")
     * @Method({"GET"})
     * @Version(from="3.0", to="3.3")
     */
    public function action4()
    {
        return $this->createSuccessApiJsonResponse('action4');
    }

    /**
     * @Route("/api-version-test")
     * @Method({"GET"})
     * @Version(from="3.4")
     */
    public function action5()
    {
        return $this->createSuccessApiJsonResponse('action5');
    }
}
