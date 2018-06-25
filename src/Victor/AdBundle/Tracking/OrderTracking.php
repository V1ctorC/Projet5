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
            $messages = "Nous avons reçu le paiement, nous attendons la réception du téléphone";
            return $messages;
        }
        elseif ($step == 2)
        {
            $messages = "Nous avons bien reçu le téléphone, nous allons le contrôler";
            return $messages;
        }
        elseif ($step == 3)
        {
            return $messages = "Le téléphone est conforme, nous allons l'envoyer prochainement !";
        }
        elseif ($step == 4)
        {
            return $messages = "Le téléphone a été confié au transporteur";
        }
        else
        {
            throw new NotFoundHttpException("Erreur dans le suivi de la commande");
        }
    }



}