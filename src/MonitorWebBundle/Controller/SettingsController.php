<?php

namespace MonitorWebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package MonitorWebBundle\Controller
 * @Route(service="monitor_web.controller.settings")
 */
class SettingsController extends Controller
{
    /**
     * @Route("/settings", name="monitor_web.settings.show")
     */
    public function showAction()
    {
        return $this->render('@MonitorWeb/Application/show.html.twig', []);
    }
}
