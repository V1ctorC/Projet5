<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function deletehimselfAction()
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $this->getUser();

        $userManager->deleteUser($user);

        return $this->redirectToRoute('victor_core_home');
    }

    public function purchasesAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();

        $listPurchases = $repository->findBy(
            array('buyer' => $user, 'sold' => 1),
            array('id' => 'desc')
        );

        if (empty($listPurchases))
        {
            throw new NotFoundHttpException("Aucun achat");
        }

        return $this->render('@VictorAd/Account/purchases.html.twig', array('listPurchases'=>$listPurchases));
    }

    public function salesAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();

        $listSales = $repository->findBy(
            array('user' => $user, 'sold' => 1)
        );

        if (empty($listSales))
        {
            throw new NotFoundHttpException("Vous n'avez rien vendu");
        }

        return $this->render('@VictorAd/Account/sales.html.twig', array('listSales'=>$listSales));
    }

    public function currentofferAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('VictorAdBundle:Offer');
        $user = $this->getUser();

        $listCurrentoffer = $repository->findBy(
            array('user' => $user, 'sold' => 0)
        );

        if (empty($listCurrentoffer))
        {
            throw new NotFoundHttpException("Vous n'avez aucune vente en cours");
        }

        return $this->render('@VictorAd/Account/currentoffer.html.twig', array('listCurrentoffer'=>$listCurrentoffer));
    }

    public function ordertrackingAction($id)
    {
        $order = $this->container->get('victor_ad.ordertracking');

        $currentuser = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $purchase = $em->getRepository('VictorAdBundle:Offer')->find($id);

        $buyer = $purchase->getBuyer();
        $seller = $purchase->getUser();
        $ordernumber = $purchase->getId();
        $paydate = $purchase->getSaledate();
        $recepdate = $purchase->getReceivedate();
        $coformdate = $purchase->getConformdate();
        $sendate = $purchase->getSendate();

        if ($buyer == $currentuser OR $seller == $currentuser)
        {
            $step = $purchase->getStep();

            $infos = $order->getorderinfos($step);
            $progress = $step * 25;

            return $this->render('@VictorAd/Account/ordertracking.html.twig', array(
                'progress'=>$progress, 'infos'=>$infos, 'ordernumber'=>$ordernumber, 'paydate'=>$paydate, 'recepdate'=>$recepdate, 'conformdate'=>$coformdate, 'sendate'=>$sendate));
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
            array('user'=> $user, 'topay'=> 1, 'paid'=> 0)
        );

        return $this->render('@VictorAd/Account/wallet.html.twig', array('listOfferToPay'=>$listOfferToPay, 'sum'=>$sum));
    }

}
