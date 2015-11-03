<?php

namespace MonitorBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MainPageController
 * @package MonitorBundle\Controller
 * @Route(service="monitor.controller.mainpage")
 */
class MainPageController extends Controller
{
    /**
     * @Route("/", name="monitor.page.mainpage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('MonitorBundle:Default/MainPage:index.html.twig');
    }
}
