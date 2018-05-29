<?php

namespace Victor\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LegalController extends Controller
{
    public function gtuAction()
    {
        return $this->render('@VictorAd/Legal/GTU.html.twig');
    }
}
