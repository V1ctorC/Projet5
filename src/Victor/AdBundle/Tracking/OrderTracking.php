<?php
/**
 * Created by PhpStorm.
 * User: VictorChevalier
 * Date: 26/04/2018
 * Time: 13:48
 */

namespace Victor\AdBundle\Tracking;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderTracking
{

    private $messages;
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function foo()
    {
        dump($this->user);
    }

    public function getpayinfos()
    {
        return $messages = "Nous avons bien reçu votre paiement";
    }

    public function getreceiveinfos()
    {
        return $messages = "Nous avons bien reçu le téléphone";
    }

    public function getconforminfos()
    {
        return $messages = "Le téléphone est bien conforme";
    }

    public function getsendinfos()
    {
        return $messages = "Nous avons envoyé le téléphone vers l'acheteur";
    }



}