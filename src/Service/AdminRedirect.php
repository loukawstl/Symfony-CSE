<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;

class AdminRedirect
{

    public function isLogged(SessionInterface $session): bool
    {
        $isLogged = $session->get('connected');

        if ($isLogged == "true") 
        {
            return true;
        }

        return false;
        
    }
}
