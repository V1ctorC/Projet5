<?php

namespace Victor\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VictorUserBundle:Default:index.html.twig');
    }
}
