<?php

namespace Victor\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VictorUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
