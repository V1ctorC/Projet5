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
                array('phone' => $phone),
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
                array('phone' => $phone),
                array('price' => 'asc'),
                5,
                0
            );

        return $this->render('@VictorAd/Advert/sellviewphone.html.twig', array('phone'=>$phone, 'listOffers'=>$listOffers));
    }



    public function ordertrackingAction($id)
    {
        $order = $this->container->get('victor_ad.ordertracking');

        $em = $this->getDoctrine()->getManager();
        $purchase = $em->getRepository('VictorAdBundle:Offer')->find($id);

        $step = $purchase->getStep();

        // Je pars du principe que $text contient le texte d'un message quelconque
        $infos = $order->getorderinfos($step);
        $progress = $step * 25;
        //$progress = $order->getprogressinfos(1);


        return $this->render('@VictorAd/Advert/add.html.twig', array('progress'=>$progress, 'infos'=>$infos));

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
    public function paiementAction($offerid)
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer')->find($offerid);

        return $this->render('@VictorAd/Advert/paiement.html.twig', array('offer'=>$offer));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function checkoutAction($offerid)
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer')->find($offerid);
        $buyer = $this->getUser();
        $offer->setBuyer($buyer);
        $offer->setSold(true);
        $em->persist($offer);
        $em->flush();
        $price = $offer->getPrice() * 100;

        \Stripe\Stripe::setApiKey("sk_test_rAGQCR0jx66px1wmcyb3me6U");

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
        $token = $_POST['stripeToken'];

        $charge = \Stripe\Charge::create([
            'amount' => $price,
            'currency' => 'eur',
            'description' => 'Deuxieme exemple',
            'source' => $token,
        ]);

        $em->persist($offer);
        $em->flush();

        return $this->redirectToRoute('victor_core_home');
    }

}
