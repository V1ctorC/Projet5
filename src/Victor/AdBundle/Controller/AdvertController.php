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
            ->render('VictorAdBundle:Advert:index.html.twig', array('nom' => 'Ici carte de la France'));

        return new Response($content);

    }

    public function homeAction($departement)
    {
        return new Response("Ici liste des annonces concernant le departement numero : " . $departement);
    }

    public function viewAction($departement ,$id)
    {
        return new Response("Ici vu d'une annonce unique de numéro : " . $id . " elle vient du département numéro : " . $departement);
    }

    public function addAction()
    {
        return new Response(("Ici ajout d'une annonce"));
    }

    public function deleteAction()
    {
        return new Response("Ici suppression d'une annonce");
    }
}
