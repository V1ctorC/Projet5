<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountController extends Controller
{
    public function accountAction()
    {
        return $this->render('@VictorAd/Account/account.html.twig');
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
            array('buyer' => $user, 'sold' => 1)
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
}
