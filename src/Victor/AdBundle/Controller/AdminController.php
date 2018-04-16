<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Victor\AdBundle\Entity\Phone;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function adminAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $users = $userManager->findUsers();

        $phone = new Phone();

        $form = $this->get('form.factory')->createBuilder(FormType::class, $phone)
            ->add('brand',    TextType::class)
            ->add('model',    TextType::class)
            ->add('capacity', IntegerType::class)
            ->add('color',    TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($phone);
                $em->flush();

                return $this->redirectToRoute('victor_core_home');
            }
        }

        return $this->render('@VictorAd/Admin/admin.html.twig', array('users'=>$users, 'form' => $form->createView()));

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
}
