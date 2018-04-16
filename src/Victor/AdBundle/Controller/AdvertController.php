<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Victor\AdBundle\Entity\Image;
use Victor\AdBundle\Entity\Offer;
use Victor\AdBundle\Entity\Phone;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
        $brand = 'Apple';
        $model = 'iPhone X';
        $color = 'Argent';
        $url = 'https://static.yes-yes.com/phones/iphone-x-argent-35.png';
        $alt = 'iPhone X';

        $phone = new Phone();
        $phone->setBrand($brand);
        $phone->setModel($model);
        $phone->setCapacity('64');
        $phone->setColor($color);

        $image = new Image();
        $image->setUrl($url);
        $image->setAlt($alt);

        $phone2 = new Phone();
        $phone2->setBrand($brand);
        $phone2->setModel($model);
        $phone2->setCapacity('256');
        $phone2->setColor($color);

        $image2 = new Image();
        $image2->setUrl($url);
        $image2->setAlt($alt);




        $phone->setImage($image);
        $phone2->setImage($image2);




        $em = $this->getDoctrine()->getManager();

        /*
        $em->persist($phone);

        $em->persist($phone2);



        $em->flush(); */



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

    public function sellofferAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $phone = $em->getRepository('VictorAdBundle:Phone')->find($id);


        $offer = new Offer();

        $offer->setPhone($phone);

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $offer);

        $formBuilder
            ->add('price',    IntegerType::class)
            ->add('save',   SubmitType::class);

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($offer);
                $em->flush();

                return $this->redirectToRoute('victor_core_home');
            }
        }


        return $this->render('@VictorAd/Advert/selloffer.html.twig', array('phone'=>$phone, 'form' => $form->createView()));
    }

    public function addofferAction()
    {
        $offer = new Offer();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $offer);

        $formBuilder
            ->add('price',    IntegerType::class);

        $form = $formBuilder->getForm();
    }


}
