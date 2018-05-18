<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Victor\AdBundle\Entity\Offer;
use Victor\AdBundle\Entity\Phone;
use Symfony\Component\HttpFoundation\Request;
use Victor\AdBundle\Form\PhoneType;

class AdminController extends Controller
{
    public function adminAction()
    {
        $userManager = $this->get('fos_user.user_manager');

        $users = $userManager->findUsers();


        return $this->render('@VictorAd/Admin/admin.html.twig', array('users'=>$users));

    }

    public function deleteAction($user)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserBy(array('id'=>$user));

        $userManager->deleteUser($user);

        return $this->redirectToRoute('victor_ad_admin');
    }

    public function deactivateAction($user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id'=>$user));

        if ($user->isEnabled() == true)
        {
            $user->setEnabled(false);

            $userManager->updateUser($user);
        }
        else
        {
            $user->setEnabled(true);

            $userManager->updateUser($user);
        }


        return $this->redirectToRoute('victor_ad_admin');
    }

    public function promoteAction($user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id'=>$user));

        $user->addRole('ROLE_ADMIN');
        $userManager->updateUser($user);

        return $this->redirectToRoute('victor_ad_admin');
    }

    public function demoteAction($user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id'=>$user));

        $user->removeRole('ROLE_ADMIN');
        $userManager->updateUser($user);

        return $this->redirectToRoute('victor_ad_admin');
    }

    public function addphoneAction(Request $request)
    {
        $phone = new Phone();

        $form = $this->get('form.factory')->create(PhoneType::class, $phone);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();

            return $this->redirectToRoute('victor_ad_admin');
        }

        return $this->render('@VictorAd/Admin/addPhone.html.twig', array('form' => $form->createView()));
    }

    public function orderAction()
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');

        $order1 = $order->findBy(array('step' => 1));

        return $this->render('@VictorAd/Admin/order.html.twig', array('order1'=>$order1));
    }

    public function order2Action()
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');

        $order2 = $order->findBy(array('step' => 2));

        return $this->render('@VictorAd/Admin/order2.html.twig', array('order2' => $order2));
    }

    public function order3Action()
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');

        $order3 = $order->findBy(array('step' => 3));

        return $this->render('@VictorAd/Admin/order3.html.twig', array('order3' => $order3));
    }

    public function order4Action()
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');

        $order4 = $order->findBy(array('step' => 4));

        return $this->render('@VictorAd/Admin/order4.html.twig', array('order4' => $order4));
    }

    public function increaseAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');
        $user = $em->getRepository('VictorUserBundle:User');
        $datetime = new \DateTime();

        $dataorder = $order->find($id);
        $userID = $dataorder->getBuyer();
        $buyer = $user->find($userID);

        $mail = $this->get('victor_ad.stepincreasemail');
        $to = $buyer->getEmail();
        $username = $buyer->getUsername();
        $subscribe = $buyer->getSubscribe();

        $step = $dataorder->getStep();


        if ($step <= 3)
        {
            $currentStep = $dataorder->getStep() + 1;
            $dataorder->setStep($currentStep);

            if ($step == 1)
            {
                $dataorder->setReceivedate($datetime);
            }
            elseif ($step == 2)
            {
                $dataorder->setConformdate($datetime);
            }
            elseif ($step == 3)
            {
                $dataorder->setSendate($datetime);
                $dataorder->setTopay(1);
            }

            $em->persist($dataorder);
            $em->flush();

            if ($subscribe == true)
            {
                $mail->SendRightMail($currentStep, $to, $username);
            }
        }
        else
        {
            throw new NotFoundHttpException("Impossible de changer le statut de la commande");
        }

        return $this->redirectToRoute('victor_ad_order');
    }

    public function sendAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');
        $user = $em->getRepository('VictorUserBundle:User');
        $datetime = new \DateTime();

        $dataorder = $order->find($id);
        $sum = $dataorder->getPrice();
        $userID = $dataorder->getBuyer();
        $userSell = $dataorder->getUser();
        $buyer = $user->find($userID);
        $seller = $user->find($userSell);



    }

    public function cancelAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');

        $orderCancel = $offer->find($id);

        $orderCancel->setBuyer(null);
        $orderCancel->setSold(0);
        $orderCancel->setStep(0);

        $em->persist($orderCancel);
        $em->flush();

        return $this->redirectToRoute('victor_ad_order');

    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function decreaseAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');

        $dataorder = $order->find($id);
        $step = $dataorder->getStep();

        if ($step > 1)
        {
            $realStep = $step - 1;
            $dataorder->setStep($realStep);
            $em->persist($dataorder);
            $em->flush();
        }
        else
        {
            throw new NotFoundHttpException('Impossible de revenir à l\'étape précedente');
        }

        return $this->redirectToRoute('victor_ad_order');
    }

    public function paymentwaitingAction()
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');

        $listPaymentWaiting = $offer->findBy(
            array('topay'=>0, 'paid'=>0, 'payrequest'=>1),
            array('user'=>'desc')
        );

        return $this->render('@VictorAd/Admin/paymentwaiting.html.twig', array('listPaymentWaiting'=>$listPaymentWaiting));
    }

    public function  paymentsendAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $em->getRepository('VictorAdBundle:Offer');
        $user = $em->getRepository('VictorUserBundle:User');

        $paidoffer = $offer->find($id);
        $userID = $paidoffer->getUser();
        $seller = $user->find($userID);

        $mail = $this->get('victor_ad.mailer');
        $price = ($paidoffer->getPrice())*0.9;
        $username = $seller->getUsername();
        $email = $seller->getEmail();
        $subscribe = $seller->getSubscribe();

        if ($subscribe == true)
        {
            $mail->sendPaidMail($email, $username, $price);
        }

        $paidoffer->setPayrequest(0);
        $paidoffer->setPaid(1);

        $em->persist($paidoffer);
        $em->flush();

        return $this->redirectToRoute('victor_ad_paymentwaiting');
    }

}
