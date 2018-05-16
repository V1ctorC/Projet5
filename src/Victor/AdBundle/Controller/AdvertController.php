<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Victor\AdBundle\Entity\Image;
use Victor\AdBundle\Entity\Offer;
use Victor\AdBundle\Entity\Phone;
use Victor\AdBundle\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

        if (empty($listPhones))
        {
            throw new NotFoundHttpException("Le téléphone que vous cherchez n'existe pas");
        }

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
                array('phone' => $phone, 'sold' => 0),
                array('price' => 'asc'),
                10,
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

        if (empty($listPhones))
        {
            throw new NotFoundHttpException("Le téléphone que vous cherchez n'existe pas");
        }


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
                array('phone' => $phone, 'sold' => 0),
                array('price' => 'asc'),
                5,
                0
            );

        return $this->render('@VictorAd/Advert/sellviewphone.html.twig', array('phone'=>$phone, 'listOffers'=>$listOffers));
    }

    public function deleteAction()
    {
        return $this->redirectToRoute('victor_ad_index');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function sellofferAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $phone = $em->getRepository('VictorAdBundle:Phone')->find($id);

        $user = $this->getUser();


        $offer = new Offer();

        $offer->setPhone($phone);
        $offer->setUser($user);

        $form = $this->get('form.factory')->create(OfferType::class, $offer);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();


            return $this->redirectToRoute('victor_core_home');

        }


        return $this->render('@VictorAd/Advert/selloffer.html.twig', array('phone'=>$phone, 'form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function summarybuyAction($id, $offerid)
    {
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository('VictorAdBundle:Phone')->find($id);

        $offer = $em->getRepository('VictorAdBundle:Offer')->find($offerid);



        return $this->render('@VictorAd/Advert/summarybuy.html.twig', array('phone'=>$phone, 'offer'=>$offer));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function checkoutAction($offerid, Request $request)
    {
        $mailer = $this->get('victor_ad.mailer');

        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer')->find($offerid);
        $buyer = $this->getUser();
        $datetime = new \DateTime();
        $buyerMail = $this->getUser()->getEmail();
        $buyerUsername = $this->getUser()->getUsername();
        $buyerSubscribe = $this->getUser()->getSubscribe();
        $offer->setBuyer($buyer);
        $offer->setSold(true);
        $offer->setStep(1);
        $offer->setSaledate($datetime);
        $em->persist($offer);
        $em->flush();
        $price = $offer->getPrice() * 100;

        \Stripe\Stripe::setApiKey("sk_test_rAGQCR0jx66px1wmcyb3me6U");

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
        //$token = $_POST['stripeToken'];
        $token = $request->request->get('stripeToken');

        $charge = \Stripe\Charge::create([
            'amount' => $price,
            'currency' => 'eur',
            'description' => 'Deuxieme exemple',
            'source' => $token,
        ]);
        if ($buyerSubscribe == true)
        {
            $mailer->sendPayMail($buyerMail, $buyerUsername);
        }


        return $this->redirectToRoute('victor_core_home');
    }

    public function checkmailAction(Request $request)
    {
        $post = $request->request->get('field');
        $em = $this->getDoctrine()->getManager();
        $usernow = $em->getRepository('VictorUserBundle:User');

        $listUser = $usernow
            ->findBy(
                array('email' => $post)
            );

        if ($listUser == null)
        {
            echo 'test';
            return $test = true;
        }
        else
        {
            header('HTTP/1.1 500 Internal Server Error');

        }

    }

    public function checkusernameAction(Request $request)
    {
        $post = $request->request->get('userfield');
        $em = $this->getDoctrine()->getManager();
        $usernow = $em->getRepository('VictorUserBundle:User');

        $listUser = $usernow
            ->findBy(
                array('username' => $post)
            );

        if ($listUser == null)
        {
            echo 'test';
            return $test = true;
        }
        else
        {
            header('HTTP/1.1 500 Internal Server Error');

        }
    }

}
