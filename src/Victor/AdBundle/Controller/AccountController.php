<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
