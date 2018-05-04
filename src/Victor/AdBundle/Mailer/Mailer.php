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
            ->setBody($body, 'text/html');

        $this->mailer->send($mail);
    }

    public function sendPayMail($to, $username)
    {
        $subject = "Nous avons bien reçu votre paiement !";
        $body = $this->templating->render('@VictorAd/Mail/PayMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendRecieveMail($to, $username)
    {
        $subject = "Nous avons reçu le téléphone";
        $body = $this->templating->render('@VictorAd/Mail/RecieveMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendConformMail($to, $username)
    {
        $subject = "Le téléphone est conforme";
        $body = $this->templating->render('@VictorAd/Mail/ConformMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendPostMail($to, $username)
    {
        $subject = "Le téléphone est en route pour chez vous :)";
        $body = $this->templating->render('@VictorAd/Mail/PostMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

}