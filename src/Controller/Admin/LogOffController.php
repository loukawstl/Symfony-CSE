<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/deconnexion')]
class LogOffController extends AbstractController
{

    #[Route('/', name: 'app_logoff')]
    public function logOff(SessionInterface $session): Response
    {
        $session->invalidate();

        return $this->redirectToRoute('app_home_page');
    }
}
