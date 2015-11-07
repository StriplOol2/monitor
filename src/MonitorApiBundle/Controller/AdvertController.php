<?php

namespace MonitorApiBundle\Controller;

use MonitorBundle\Service\AdvertService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use VLru\ApiBundle\Configuration\Route\Version;
use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Controller\BaseApiController;
use VLru\ApiBundle\Request\Form\FormValidationException;
use VLru\ApiBundle\Configuration\Params;

/**
 * @package MonitorApiBundle\Controller
 * @Route(service="monitor_api.controller.advert")
 */
class AdvertController extends BaseApiController
{
    /**
     * @var AdvertService
     */
    protected $advertService;

    /**
     * AdvertController constructor.
     * @param AdvertService $advertService
     */
    public function __construct(AdvertService $advertService)
    {
        $this->advertService = $advertService;
    }

    /**
     * @Route("/users/{auth_key}/searches/{search_id}/adverts", name="monitor_api.advert.all")
     * @Method({"GET"})
     * @Version(from="1.0")
     *
     * @Params\String("authKey", mapping={"auth_key"}, required=true)
     * @Params\Integer("searchId", mapping={"search_id"}, required=true)
     *
     * @param string $authKey
     * @param int $searchId
     * @param Request $request
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     */
    public function getAdvertAction($authKey, $searchId, Request $request)
    {
        $lastUpdateTimestamp = $request->query->get('timestamp');
        $result = $this->advertService->getAdverts($authKey, $searchId, $lastUpdateTimestamp);
        return $this->createSuccessApiJsonResponse($result, ['default']);
    }
}
