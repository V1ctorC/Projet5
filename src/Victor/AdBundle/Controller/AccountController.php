<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Victor\AdBundle\Entity\Offer;
use Victor\AdBundle\Form\OfferType;

class AccountController extends Controller
{
    public function accountAction()
    {
        $user = $this->getUser();
        $subscribe = $user->getSubscribe();
        return $this->render('@VictorAd/Account/account.html.twig', array('subscribe'=>$subscribe));
    }

    public function deleteAction()
    {
        return $this->render('@VictorAd/Account/delete.html.twig');
    }

    public function purchasesAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();

        $listPurchases = $repository->findBy(
            array('buyer' => $user, 'sold' => 1),
            array('id' => 'desc')
        );

        return $this->render('@VictorAd/Account/purchases.html.twig', array('listPurchases'=>$listPurchases));
    }

    public function salesAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();

        $listSales = $repository->findBy(
            array('user' => $user, 'sold' => 1)
        );

        return $this->render('@VictorAd/Account/sales.html.twig', array('listSales'=>$listSales));
    }

    public function currentofferAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();

        $listCurrentoffer = $repository->findBy(
            array('user' => $user, 'sold' => 0)
        );

        return $this->render('@VictorAd/Account/currentoffer.html.twig', array('listCurrentoffer'=>$listCurrentoffer));
    }

    public function ordertrackingAction(Offer $purchase)
    {
        $order = $this->container->get('victor_ad.ordertracking');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('VictorUserBundle:User');

        $currentuser = $this->getUser();

        $buyerID = $purchase->getBuyer();
        $buyer = $user->find($buyerID);
        $sellerID = $purchase->getUser();
        $seller = $user->find($sellerID);
        $phone = $em->getRepository('VictorAdBundle:Phone')->find($purchase->getPhone());
        $ordernumber = $purchase->getId();
        $paydate = $purchase->getSaledate();
        $recepdate = $purchase->getReceivedate();
        $coformdate = $purchase->getConformdate();
        $sendate = $purchase->getSendate();

        if ($buyerID == $currentuser OR $sellerID == $currentuser OR $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            $step = $purchase->getStep();

            $infos = $order->getorderinfos($step);
            $progress = $step * 25;

            return $this->render('@VictorAd/Account/ordertracking.html.twig', array(
                'progress'=>$progress, 'buyer'=>$buyer, 'seller'=>$seller, 'phone'=>$phone, 'infos'=>$infos, 'ordernumber'=>$ordernumber, 'paydate'=>$paydate, 'recepdate'=>$recepdate, 'conformdate'=>$coformdate, 'sendate'=>$sendate));
        }

        else
        {
            throw new NotFoundHttpException("Il n'y aucune commande portant ce numéro");
        }

    }

    public function subscriptionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $usersub = $user->getSubscribe();

        if ($usersub == true)
        {
            $user->setSubscribe(false);

        } else {
            $user->setSubscribe(true);
        }

        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('victor_ad_account');

    }

    public function walletAction()
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();
        $sum = 0;

        $listOfferToPay = $offer->findBy(
            array('user'=> $user, 'topay'=> 1, 'paid'=> 0, 'payrequest'=>0)
        );

        foreach ($listOfferToPay as $offerToPay)
        {
            $sum = ($sum + $offerToPay->getPrice())*0.9;
        }

        return $this->render('@VictorAd/Account/wallet.html.twig', array('sum'=>$sum));
    }

    public function confirmwalletAction()
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();
        $sum = 0;

        $listOfferToPay = $offer->findBy(
            array('user'=> $user, 'topay'=> 1, 'paid'=> 0, 'payrequest'=>0)
        );

        foreach ($listOfferToPay as $offerToPay)
        {
            $sum = ($sum + $offerToPay->getPrice())*0.9;
        }

        return $this->render('@VictorAd/Account/confirmwallet.html.twig', array('user'=>$user, 'sum'=>$sum));

    }

    public function payrequestAction()
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');
        $user = $em->getRepository('VictorUserBundle:User');
        $mail = $this->get('victor_ad.mailer');
        $userID = $this->getUser();
        $customer = $user->find($userID);
        $customerMail = $customer->getEmail();
        $customerUsername = $customer->getUsername();

        $listOfferToPay = $offer->findBy(
            array('user'=> $userID, 'topay'=> 1, 'paid'=> 0, 'payrequest'=>0)
        );

        foreach ($listOfferToPay as $offerToPay)
        {
            $offerToPay->setPayrequest(1);
            $offerToPay->setTopay(0);
        }

        $em->persist($offerToPay);
        $em->flush();

        $mail->sendRequestMail($customerMail, $customerUsername);

        return $this->render('@VictorAd/Account/afterwalletrequest.html.twig');

    }

    public function deleteofferAction(Offer $offertodelete)
    {
        $em = $this->getDoctrine()->getManager();
        $user= $this->getUser();

        $author = $offertodelete->getUser();
        $sold = $offertodelete->getSold();

        if (($author == $user) && ($sold == 0))
        {
            $em->remove($offertodelete);
            $em->flush();
            return $this->redirectToRoute('victor_ad_currentoffer');
        }
        else
        {
            throw new NotFoundHttpException("Vous ne pouvez pas supprimer cette offre");
        }
    }

    public function editofferAction(Offer $offer, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $author = $offer->getUser();
        $sold = $offer->getSold();

        if (($author !== $user) || ($sold == 1))
        {
            throw new NotFoundHttpException('Vous ne pouvez pas modifier cette offre');
        }

        $form = $this->get('form.factory')->create(OfferType::class, $offer);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->flush();

            return $this->redirectToRoute('victor_ad_account');
        }

        return $this->render('@VictorAd/Account/editoffer.html.twig', array('offer'=>$offer, 'form'=>$form->createView()));
    }

    public function myinfosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userID = $this->getUser();
        $user = $em->getRepository('VictorUserBundle:User')->find($userID);

        if ($user != null)
        {
            $infosJson = $user->to_json();

            return new Response($infosJson);
        }

        return new NotFoundHttpException('Impossible de récuperer les données');
    }

    public function verificationpasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('VictorUserBundle:User');
        $currentUserID = $this->getUser();
        $currenntUser = $user->find($currentUserID);
        $currenntUserUsername = $currenntUser->getUsername();
        $currenntUserMail = $currenntUser->getEmail();
        $mail = $this->get('victor_ad.mailer');
        $pass = $currenntUser->getPassword();
        $inputPassword = $request->request->get('_password');

        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($currenntUser);
        $encoded_pass = $encoder->encodePassword($inputPassword, $currenntUser->getSalt());

        if ($encoded_pass == $pass)
        {
            $mail->sendDeleteAccountMail($currenntUserMail, $currenntUserUsername);
            $userManager = $this->get('fos_user.user_manager');
            $userManager->deleteUser($currentUserID);

            return $this->redirectToRoute('victor_ad_deletecomplete');
        }
        else
        {
            return $this->redirectToRoute('victor_ad_deleteconfirm');
        }

    }

    public function deletecompleteAction()
    {
        return $this->render('@VictorAd/Account/deletecomplete.html.twig');
    }

}
