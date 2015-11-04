<?php

namespace MonitorApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package MonitorApiBundle\Controller
 * @Route(service="monitor_api.controller.search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/", name="monitor_api.page.search")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return new JsonResponse(['test']);
    }
}