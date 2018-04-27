<?php
/**
 * Created by PhpStorm.
 * User: VictorChevalier
 * Date: 27/04/2018
 * Time: 14:17
 */

namespace Victor\AdBundle\Mailer;


use Symfony\Component\Templating\EngineInterface;

class Mailer
{

    protected $mailer;


    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendPayMail($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($mail);
    }

}