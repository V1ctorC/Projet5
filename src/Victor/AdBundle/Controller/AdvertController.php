<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Victor\AdBundle\Entity\Offer;
use Victor\AdBundle\Form\OfferType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdvertController extends Controller
{
    public function indexAction()
    {
        return $this->render('VictorAdBundle:Advert:index.html.twig', array('nom' => ''));

    }

    public function sellAction()
    {
        return $this->render('@VictorAd/Advert/sell.html.twig');
    }

    public function buyAction()
    {
        return $this->render('@VictorAd/Advert/buy.html.twig');
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


            return $this->redirectToRoute('victor_ad_afterselloffer');

        }


        return $this->render('@VictorAd/Advert/selloffer.html.twig', array('phone'=>$phone, 'form' => $form->createView()));
    }

    public function aftersellofferAction()
    {
        return $this->render('@VictorAd/Advert/afterselloffer.html.twig');
    }

    public function afterbuyAction()
    {
        return $this->render('@VictorAd/Advert/afterbuy.html.twig');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function summarybuyAction($id, $offerid)
    {
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository('VictorAdBundle:Phone')->find($id);

        $offer = $em->getRepository('VictorAdBundle:Offer')->find($offerid);
        $phoneOfferID = $offer->getPhone();

        if ($offer == null || $offer->getSold() == 1)
        {
            throw new NotFoundHttpException('L\'offre que vous avez selectionné n\'est pas disponible');
        }
        elseif ($phone != $phoneOfferID)
        {
            throw new NotFoundHttpException('L\'offre que vous avez selectionné ne concerne pas ce téléphone');
        }
        else
        {
            return $this->render('@VictorAd/Advert/summarybuy.html.twig', array('phone'=>$phone, 'offer'=>$offer));
        }
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function checkoutAction($offerid, Request $request)
    {
        $mailer = $this->get('victor_ad.mailer');

        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer')->find($offerid);
        $user = $em->getRepository('VictorUserBundle:User');
        $phone = $em->getRepository('VictorAdBundle:Phone');
        $sellerID = $offer->getUser();
        $seller = $user->find($sellerID);
        $buyer = $this->getUser();
        $datetime = new \DateTime();
        $buyerMail = $buyer->getEmail();
        $buyerUsername = $buyer->getUsername();
        $buyerSubscribe = $buyer->getSubscribe();
        $sellerMail = $seller->getEmail();
        $sellerUsername = $seller->getUsername();
        $sellerSubscribe = $seller->getSubscribe();
        $phoneID = $offer->getPhone();
        $phoneModel = $phone->find($phoneID)->getModel();

        $offer->setBuyer($buyer);
        $offer->setSold(true);
        $offer->setStep(1);
        $offer->setSaledate($datetime);
        $em->persist($offer);
        $em->flush();
        $price = $offer->getPrice() * 100;

        \Stripe\Stripe::setApiKey("sk_test_rAGQCR0jx66px1wmcyb3me6U");

        $token = $request->request->get('stripeToken');

        $charge = \Stripe\Charge::create([
            'amount' => $price,
            'currency' => 'eur',
            'description' => 'Achat téléphone',
            'source' => $token,
        ]);
        if ($buyerSubscribe == true)
        {
            $mailer->sendPayMail($buyerMail, $buyerUsername);
        }
        if ($sellerSubscribe == true)
        {
            $mailer->sendsoldMail($sellerMail, $sellerUsername, $phoneModel);
        }

        return $this->redirectToRoute('victor_ad_afterbuy');
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

    public function allofferAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');

        $min = $request->request->get('min');
        $max = $request->request->get('max');

        if ($min == null)
        {
            $alloffer = $offer->findBy(
                array('sold' => 0),
                array('price' => 'asc')
            );
        }
        else
        {
            $alloffer = $offer->FindByPrice($min, $max);
        }

        return $this->render('@VictorAd/Advert/alloffer.html.twig', array('alloffer' => $alloffer));
    }

}
