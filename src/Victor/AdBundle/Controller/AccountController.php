<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller
{
    public function accountAction()
    {
        return $this->render('@VictorAd/Account/account.html.twig');
    }

    public function dataAction()
    {
        return $this->render('@VictorAd/Account/data.html.twig');
    }
}
