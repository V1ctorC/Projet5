<?php

namespace Victor\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Victor\CoreBundle\VictorCoreBundle;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('VictorCoreBundle:Core:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        $session = $request->getSession();

        $session->getFlashBag()->add('info', 'La page demandé n\'est pas encore disponible');

        return $this->redirectToRoute('victor_core_home');
    }
}
