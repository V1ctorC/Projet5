<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this
            ->get('templating')
            ->render('VictorAdBundle:Advert:index.html.twig', array('nom' => 'Victor'));

        return new Response($content);
    }
}
