<?php

namespace MonitorWebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @package MonitorWebBundle\Controller
 * @Route(service="monitor_web.controller.profile")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="monitor_web.profile.show")
     */
    public function showAction()
    {
        return $this->render('@MonitorWeb/Application/show.html.twig', []);
    }
}
