<?php

namespace MonitorApiBundle\Controller;

use MonitorApiBundle\Form\SearchType;
use MonitorBundle\Entity\Search;
use MonitorBundle\Service\SearchService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/users/{auth_key}/searches/{search_id}/update", name="monitor_api.search.update.activated")
     * @Method({"PUT"})
     * @Version(from="1.0")
     *
     * @Params\String("authKey", mapping={"auth_key"}, required=true)
     * @Params\Integer("searchId", mapping={"search_id"}, required=true)
     *
     * @param string $authKey
     * @param int $searchId
     * @return \VLru\ApiBundle\Response\ApiJsonResponse
     */
    public function updateActivated($authKey, $searchId)
    {
        $result = $this->searchService->updateActivated($authKey, $searchId);
        return $this->createSuccessApiJsonResponse(
            $result,
            ['default']
        );
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
    public function deleteSearchAction($authKey, $searchId)
    {
        $result = $this->searchService->deleteSearch($authKey, $searchId);
        return $this->createSuccessApiJsonResponse(
            $result,
            ['default']
        );
    }

    /**
     * @Route("/users/{auth_key}/searches/{search_id}", name="monitor_api.search.update")
     * @Method({"PUT"})
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
    public function updateSearchAction($authKey, $searchId, Request $request)
    {
        $form = $this->createForm(new SearchType());
        $form->submit($request->query->all());
        $form->getErrors();
        if ($form->isValid()) {
            /** @var Search $search */
            $search = $form->getData();
            $search->setId($searchId);
            $result = $this->searchService->updateSearch($search, $authKey);
            return $this->createSuccessApiJsonResponse(
                $result,
                ['default']
            );
        }

        throw new FormValidationException($form->getErrors(true));
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
    public function createSearchAction($authKey, Request $request)
    {
        $form = $this->createForm(new SearchType());
        $form->submit($request->request->all());
        $form->getErrors();
        if ($form->isValid()) {
            /** @var Search $search */
            $search = $form->getData();
            $result = $this->searchService->createSearch($search, $authKey);
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
    public function getSearchesAction($authKey)
    {
        $searches = $this->searchService->getSearches($authKey);
        return $this->createSuccessApiJsonResponse($searches, ['default']);
    }
}
