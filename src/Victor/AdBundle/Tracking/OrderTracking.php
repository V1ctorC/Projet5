<?php
/**
 * Created by PhpStorm.
 * User: VictorChevalier
 * Date: 26/04/2018
 * Time: 13:48
 */

namespace Victor\AdBundle\Tracking;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderTracking
{

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
            return $messages = "Le téléphone est conforme, nous allons donc l'envoyer ! :)";
        }
        elseif ($step == 4)
        {
            return $messages = "Le téléphone est parti vers sa nouvelle maison ;)";
        }
        else
        {
            throw new NotFoundHttpException("Erreur dans le suivi de la commande");
        }
    }



}