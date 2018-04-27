<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $order2 = $order->findBy(array('step' => 2));
        $order3 = $order->findBy(array('step' => 3));
        $order4 = $order->findBy(array('step' => 4));



        return $this->render('@VictorAd/Admin/order.html.twig', array('order1'=>$order1, 'order2'=>$order2, 'order3'=>$order3, 'order4'=>$order4, ));
    }

    public function increaseAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository('VictorAdBundle:Offer');
        $user = $em->getRepository('VictorUserBundle:User');

        $dataorder = $order->find($id);
        $userID = $dataorder->getBuyer();
        $buyer = $user->find($userID);

        $mail = $this->get('victor_ad.mailer');
        $usermail = $buyer->getEmail();
        $username = $buyer->getUsername();


        if ($dataorder->getStep() <= 3)
        {
            $currentStep = $dataorder->getStep() + 1;
            $dataorder->setStep($currentStep);

            $em->persist($dataorder);
            $em->flush();
            $mail->sendPostMail($usermail, $username);
        }
        else
        {
            throw new NotFoundHttpException("Impossible de changer le statut de la commande");
        }

        return $this->redirectToRoute('victor_ad_order');
    }
}
