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

    public function sendPaidMail($to, $username, $price)
    {
        $subject = "Votre paiement est en route !";
        $body = $this->templating->render('VictorAdBundle:Mail:PaidMail.html.twig', array('username'=>$username, 'price'=>$price));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendsoldMail($to, $username, $model)
    {
        $subject = "Votre téléphone est vendu !";
        $body = $this->templating->render('@VictorAd/Mail/SoldMail.html.twig', array('username'=>$username, 'model'=>$model));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendRecieveSellerMail($to, $username)
    {
        $subject = "Nous avons bien reçu votre téléphone";
        $body = $this->templating->render('VictorAdBundle:Mail:RecieveSellerMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendConformSellerMail($to, $username)
    {
        $subject = "Nous avons bien reçu votre téléphone";
        $body = $this->templating->render('VictorAdBundle:Mail:ConformSellerMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendPostSellerMail($to, $username)
    {
        $subject = "Nous avons bien reçu votre téléphone";
        $body = $this->templating->render('VictorAdBundle:Mail:PostSellerMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

    public function sendRequestMail($to, $username)
    {
        $subject = "Nous avons reçu votre demande de paiement";
        $body = $this->templating->render('@VictorAd/Mail/RequestMail.html.twig', array('username'=>$username));

        $this->sendMessage($to, $subject, $body);
    }

}