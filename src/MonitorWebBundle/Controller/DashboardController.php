<?php

namespace MonitorWebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package MonitorWebBundle\Controller
 * @Route(service="monitor_web.controller.dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="monitor_web.dashboard.show")
     */
    public function showAction()
    {
        return $this->render('@MonitorWeb/Dashboard/show.html.twig', []);
    }
}
