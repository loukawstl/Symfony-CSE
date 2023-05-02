<?php
namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyAnswer;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SurveyController extends AbstractController
{
    #[Route('/survey/submit', name: 'app_survey_submit', methods: ['POST'])]
    public function sendForm(Request $request, SurveyRepository $surveyRepository, EntityManagerInterface $manager)
    {
        $survey = $surveyRepository->find($request->request->get('surveyId'));
        $optionId = $request->request->get('surveyOption');
        $check = true;

        if ($optionId = null){
            $this->addFlash('surveyError', 'Erreur: Vous ne pouvez pas envoyer un formulaire vide.');
            $check = false;
        }

        dd($optionId);

        if ($check){
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->setAnswer($optionId);
            $surveyAnswer->setSurvey($survey);

            $manager->persist($surveyAnswer);
            $manager->flush();
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    #[Route('/confirmation', name: 'app_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('sidebar_left/confirmation.html.twig');
    }
    
}
