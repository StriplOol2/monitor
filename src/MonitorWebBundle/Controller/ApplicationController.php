<?php

namespace MonitorWebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package MonitorWebBundle\Controller
 * @Route(service="monitor_web.controller.application")
 */
class ApplicationController extends Controller
{
    /**
     * @Route("/", name="monitor_web.application.show")
     */
    public function showAction()
    {
        return $this->render('@MonitorWeb/Application/show.html.twig', []);
    }
}
