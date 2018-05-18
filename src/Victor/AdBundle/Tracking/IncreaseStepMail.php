<?php
/**
 * Created by PhpStorm.
 * User: VictorChevalier
 * Date: 27/04/2018
 * Time: 16:38
 */

namespace Victor\AdBundle\Tracking;


class IncreaseStepMail
{

    private $mailer;

    public function __construct(\Victor\AdBundle\Mailer\Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function SendRightMail($step, $to, $username)
    {
        if ($step == 2)
        {
            $this->mailer->sendRecieveMail($to, $username);
        }
        elseif ($step == 3)
        {
            $this->mailer->sendConformMail($to, $username);
        }
        elseif ($step == 4)
        {
            $this->mailer->sendPostMail($to, $username);
        }
    }

    public function SendSellerRightMail($step, $toSeller, $usernameSeller)
    {
        if ($step == 2)
        {
            $this->mailer->sendRecieveSellerMail($toSeller, $usernameSeller);
        }
        elseif ($step == 3)
        {
            $this->mailer->sendConformSellerMail($toSeller, $usernameSeller);
        }
        elseif ($step == 4)
        {
            $this->mailer->sendPostSellerMail($toSeller, $usernameSeller);
        }
    }
}