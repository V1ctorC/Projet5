<?php
/**
 * Created by PhpStorm.
 * User: VictorChevalier
 * Date: 26/04/2018
 * Time: 13:48
 */

namespace Victor\AdBundle\Tracking;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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


    public function getorderinfos($step)
    {
        if ($step == 1)
        {
            $messages = "Nous avons reçu le paiement, le vendeur va nous envoyer le produit";
            return $messages;
        }
        elseif ($step == 2)
        {
            $messages = "Nous avons bien reçu le téléphone, nous allons le contrôler";
            return $messages;
        }
        elseif ($step == 3)
        {
            return $messages = "Le téléphone est conforme, nous allons donc vous l'envoyer ! :)";
        }
        elseif ($step == 4)
        {
            return $messages = "Le téléphone est parti vers chez vous ;)";
        }
        else
        {
            throw new NotFoundHttpException("Erreur dans le suivi de la commande");
        }
    }

    public function sendorderinfos($user)
    {

    }



}