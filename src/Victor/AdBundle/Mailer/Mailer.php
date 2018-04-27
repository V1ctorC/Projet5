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
    protected $templating;


    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    protected function sendMessage($to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom("vetudes.noreply@gmail.com")
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body);

        $this->mailer->send($mail);
    }

    public function sendPayMail($to)
    {
        $subject = ("Votre commande a bougé !");
        $body = $this->templating->render('@VictorAd/Mail/test.html.twig');

        $this->sendMessage($to, $subject, $body);
    }

}