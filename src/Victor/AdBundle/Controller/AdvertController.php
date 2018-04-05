<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Victor\AdBundle\Entity\Image;
use Victor\AdBundle\Entity\Offer;
use Victor\AdBundle\Entity\Phone;
use Victor\AdBundle\Repository\PhoneRepository;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this->render('VictorAdBundle:Advert:index.html.twig', array('nom' => ''));

        return new Response($content);

    }

    public function sellAction()
    {
        $content = $this->render('@VictorAd/Advert/sell.html.twig');

        return new Response($content);
    }

    public function buyAction()
    {
        $content = $this->render('@VictorAd/Advert/buy.html.twig');

        return new Response($content);
    }

    public function buyviewAction($phone)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Phone');

        $listPhones = $repository->findBy(
            array('model' => $phone)
        );

        return $this->render('@VictorAd/Advert/buyview.html.twig', array('listPhones'=>$listPhones));
    }

    public function  buyviewphoneAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $phone = $em->getRepository('VictorAdBundle:Phone')->find($id);

        if (null === $phone) {
            throw new NotFoundHttpException("Le téléphone que vous cherchez n'existe pas");
        }

        $listOffers = $em
            ->getRepository('VictorAdBundle:Offer')
            ->findBy(
                array('phone' => $phone),
                array('price' => 'asc'),
                5,
                0
            );

        return $this->render('@VictorAd/Advert/buyviewphone.html.twig', array('phone'=>$phone, 'listOffers'=>$listOffers));
    }

    public function sellviewAction($phone)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Phone');

        $listPhones = $repository->findBy(
            array('model' => $phone)
        );


        return $this->render('@VictorAd/Advert/sellview.html.twig', array('listPhones'=>$listPhones));

    }

    public function sellviewphoneAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $phone = $em->getRepository('VictorAdBundle:Phone')->find($id);

        if (null === $phone) {
            throw new NotFoundHttpException("Le téléphone que vous cherchez n'existe pas");
        }

        $listOffers = $em
            ->getRepository('VictorAdBundle:Offer')
            ->findBy(
                array('phone' => $phone),
                array('price' => 'asc'),
                5,
                0
            );

        return $this->render('@VictorAd/Advert/sellviewphone.html.twig', array('phone'=>$phone, 'listOffers'=>$listOffers));
    }

    public function homeAction($departement, $page)
    {
        if ($page < 1)
        {
            throw new NotFoundHttpException('La page ' . $page . ' n\'existe pas.');
        }


        return $this->render('@VictorAd/Advert/buy.html.twig', array('listAdverts'=>$listAdverts));
    }

    public function viewAction($id)
    {
        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => 1,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime());
        return $this->render('VictorAdBundle:Advert:view.html.twig', array('advert' => $advert));
    }

    public function addAction(Request $request)
    {
        $phone = new Phone();
        $phone->setBrand('Apple');
        $phone->setModel('iPhone FUN2');
        $phone->setCapacity('256');
        $phone->setColor('Argent');

        $image = new Image();
        $image->setUrl('data:image/png;base64,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');
        $image->setAlt('image de test');

        $offer = new Offer();
        $offer->setPrice('345');
        $offer->setPhone($phone);

        $offer2 = new Offer();
        $offer2->setPrice('600');
        $offer2->setPhone($phone);

        $offer3 = new Offer();
        $offer3->setPrice('487');
        $offer3->setPhone($phone);

        $phone->setImage($image);

        /*

        $em = $this->getDoctrine()->getManager();

        $em->persist($phone);

        $em->persist($offer);
        $em->persist($offer2);
        $em->persist($offer3);

        $em->flush();

        */

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
