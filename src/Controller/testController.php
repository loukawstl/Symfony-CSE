<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class testController extends AbstractController
{
    #[Route('/test/untest', name: 'test')]
    public function test(): Response
    {
        return $this->render('test.html.twig',[
            
        ]);
    }
}