<?php

namespace MonitorApiBundle\Controller;

use MonitorBundle\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VLru\ApiBundle\Configuration\Route\Version;
use VLru\ApiBundle\Configuration\Params;
use VLru\ApiBundle\Controller\BaseApiController;

/**
 * @package MonitorApiBundle\Controller
 * @Route(service="monitor_api.controller.user")
 */
class UserController extends BaseApiController
{
    /** @var UserService */
    protected $userService;

    /**
     * UserController constructor.
     * @param $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/users/auth/{login}/{password}", name="monitor_api.users.login")
     * @Method({"GET"})
     * @Version(from="1.0")
     *
     * @Params\String("login", required=true)
     * @Params\String("password", required=true)
     *
     * @param string $login
     * @param string $password
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     */
    public function auth($login, $password)
    {
        $user = $this->userService->auth($login, $password);
        return $this->createSuccessApiJsonResponse(
            $user,
            ['default']
        );
    }
}
