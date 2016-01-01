<?php

namespace MonitorWebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package MonitorWebBundle\Controller
 * @Route(service="monitor_web.controller.static_page")
 */
class StaticPageController extends Controller
{
    /**
     * @Route("/help", name="monitor_web.static_page.help.show")
     */
    public function showHelpAction()
    {
        return $this->render('@MonitorWeb/Application/show.html.twig', []);
    }
}
