<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class headerController extends AbstractController
{
    #[Route('/navbar', name: 'navbar')]
    public function test(): Response
    {
        return $this->render('Header/header.html.twig',[
            
        ]);
    }
}