<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BackHomePageController extends AbstractController
{
    #[Route('/back/listeHomepage', name: 'ListeHomepage')]
    public function test(): Response
    {
        return $this->render('backHomePage/listeText.html.twig',[
            
        ]);
    }
}
