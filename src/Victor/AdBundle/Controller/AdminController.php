<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

        $userManager->deleteUser($user);
    }
}
