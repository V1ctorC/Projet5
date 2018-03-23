<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this->render('VictorAdBundle:Advert:index.html.twig', array('nom' => 'Ici carte de la France'));

        return new Response($content);

    }

    public function homeAction($departement, $page)
    {
        if ($page < 1)
        {
            throw new NotFoundHttpException('La page ' . $page . ' n\'existe pas.');
        }

        return $this->render('VictorAdBundle:Advert:home.html.twig', array('departement' => $departement, 'page' => $page));
    }

    public function viewAction($departement ,$id)
    {
        return $this->render('VictorAdBundle:Advert:view.html.twig', array('id' => $id));
    }

    public function addAction(Request $request)
    {
        $session = $request->getSession();

        // Bien sûr, cette méthode devra réellement ajouter l'annonce

        // Mais faisons comme si c'était le cas
        $session->getFlashBag()->add('info', 'Annonce bien enregistrée');

        // Le « flashBag » est ce qui contient les messages flash dans la session
        // Il peut bien sûr contenir plusieurs messages :
        $session->getFlashBag()->add('info', 'Oui oui, elle est bien enregistrée !');

        // Puis on redirige vers la page de visualisation de cette annonce
        return $this->redirectToRoute('victor_ad_view', array('id' => 5, 'departement' => 77));
    }

    public function deleteAction()
    {
        return $this->redirectToRoute('victor_ad_index');
    }
}
