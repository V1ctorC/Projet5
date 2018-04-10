<?php

namespace Victor\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VictorCoreBundle:Default:index.html.twig');
    }
}
