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
    private $state;
    private $progress;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
        //$utilisateur= $this->get('security.context')->getToken()->getUser();
    }


    public function getorderinfos($state)
    {
        if ($state == 1)
        {
            $messages = "test tableaux";
            return array($messages);
        }
        elseif ($state == 2)
        {
            return $messages = "Nous avons bien reçu le téléphone";
        }
        elseif ($state == 3)
        {
            return $messages = "Le téléphone est conforme";
        }
        elseif ($state == 4)
        {
            return $messages = "Nous avons envoyé le téléphone vers l'acheteur";
        }
        else
        {
            throw new \Exception("Erreur dans le suivi de la commande");
        }
    }

    public function getprogressinfos($state)
    {
        if ($state == 1)
        {
            $progress = 25;
            return $progress;
        }
        elseif ($state == 2)
        {
            $progress = 50;
            return $progress;
        }
        elseif ($state == 3)
        {
            return $messages = "Le téléphone est conforme";
        }
        elseif ($state == 4)
        {
            return $messages = "Nous avons envoyé le téléphone vers l'acheteur";
        }
        else
        {
            throw new \Exception("Erreur dans le suivi de la commande");
        }
    }

    public function sendorderinfos($user)
    {

    }



}