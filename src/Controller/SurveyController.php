<?php
namespace App\Controller;

use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{
    #[Route('/SendForm', name: 'app_send_form', methods: ['GET', 'POST'])]
    public function sendForm(Request $request, $id): Response
    {
        
    }

    #[Route('/Confirmation', name: 'app_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('sidebar_left/confirmation.html.twig');
    }
}