<?php
namespace App\Controller;

use App\Entity\SurveyOption;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
    


class SurveyController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/send-form/{id}', name: 'app_send_form', methods: ['GET', 'POST'])]
    public function sendForm(Request $request, $id)
    {
        $surveyId = $request->request->get('survey_id');
        $optionId = $request->request->get('survey_option');
        $option = $this->entityManager->getRepository(SurveyOption::class)->find($optionId);
        $response = new SurveyResponse();
        $response->setSurveyOption($option);
        $entityManager = $this->entityManager->getManager();
        $entityManager->persist($response);
        $entityManager->flush();
        // redirigez l'utilisateur vers une page de confirmation
        return $this->redirectToRoute('app_confirmation', ['id' => $id]);
    }

    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('sidebar_left/confirmation.html.twig');
    }
    
}
