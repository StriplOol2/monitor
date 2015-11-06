<?php

namespace MonitorApiBundle\Controller;

use MonitorApiBundle\Entity\Search;
use MonitorApiBundle\Form\SearchType;
use MonitorBundle\Service\SearchService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use VLru\ApiBundle\Configuration\Route\Version;
use VLru\ApiBundle\Controller\BaseApiController;
use VLru\ApiBundle\Request\Form\FormValidationException;
use VLru\ApiBundle\Configuration\Params;

/**
 * @package MonitorApiBundle\Controller
 * @Route(service="monitor_api.controller.search")
 */
class SearchController extends BaseApiController
{
    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * SearchController constructor.
     * @param SearchService $searchService
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @Route("/", name="monitor_api.page.search")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return new JsonResponse(['test']);
    }

    /**
     * @Route("/users/{user_id}/searches", name="monitor_api.search.create")
     * @Method({"POST"})
     * @Version(from="1.0")
     *
     * @Params\String("userId", mapping={"user_id"}, required=true)
     *
     * @param int $userId
     * @param Request $request
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     */
    public function createSearch($userId, Request $request)
    {
        $form = $this->createForm(new SearchType());
        $form->submit($request->request->all());
        $form->getErrors();
        if ($form->isValid()) {
            /** @var Search $search */
            $search = $form->getData();
            $search->setUserId($userId);
            $result = $this->searchService->createSearchByType($search->getType(), $search->getUserId());
            return $this->createSuccessApiJsonResponse(
                $result,
                ['default']
            );
        }

        throw new FormValidationException($form->getErrors(true));
    }

    /**
     * @Route("/users/{user_id}/searches", name="monitor_api.search.all")
     * @Method({"GET"})
     * @Version(from="1.0")
     *
     * @Params\String("userId", mapping={"user_id"}, required=true)
     *
     * @param int $userId
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     * @throws \MonitorBundle\Exception\UserNotFoundException
     */
    public function getSearches($userId)
    {
        $searches = $this->searchService->getSearches($userId);
        return $this->createSuccessApiJsonResponse($searches, ['default']);
    }
}
