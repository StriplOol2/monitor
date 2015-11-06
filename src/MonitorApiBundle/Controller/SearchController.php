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
     * @Route("/users/{auth_key}/searches/{search_id}", name="monitor_api.search.delete")
     * @Method({"DELETE"})
     * @Version(from="1.0")
     *
     * @Params\String("authKey", mapping={"auth_key"}, required=true)
     * @Params\Integer("searchId", mapping={"search_id"}, required=true)
     *
     * @param string $authKey
     * @param int $searchId
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     */
    public function deleteSearch($authKey, $searchId)
    {
        /** @var Search $search */
        $result = $this->searchService->deleteSearch($authKey, $searchId);
        return $this->createSuccessApiJsonResponse(
            $result,
            ['default']
        );
    }

    /**
     * @Route("/users/{auth_key}/searches", name="monitor_api.search.create")
     * @Method({"POST"})
     * @Version(from="1.0")
     *
     * @Params\String("authKey", mapping={"auth_key"}, required=true)
     *
     * @param string $authKey
     * @param Request $request
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     */
    public function createSearch($authKey, Request $request)
    {
        $form = $this->createForm(new SearchType());
        $form->submit($request->request->all());
        $form->getErrors();
        if ($form->isValid()) {
            /** @var Search $search */
            $search = $form->getData();
            $result = $this->searchService->createSearchByType($search->getType(), $authKey);
            return $this->createSuccessApiJsonResponse(
                $result,
                ['default']
            );
        }

        throw new FormValidationException($form->getErrors(true));
    }

    /**
     * @Route("/users/{auth_key}/searches", name="monitor_api.search.all")
     * @Method({"GET"})
     * @Version(from="1.0")
     *
     * @Params\String("authKey", mapping={"auth_key"}, required=true)
     *
     * @param string $authKey
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     * @throws \MonitorBundle\Exception\UserNotFoundException
     */
    public function getSearches($authKey)
    {
        $searches = $this->searchService->getSearches($authKey);
        return $this->createSuccessApiJsonResponse($searches, ['default']);
    }
}
