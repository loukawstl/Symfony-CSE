<?php
namespace App\Controller;

use DateTime;
use App\Entity\Survey;
use App\Entity\SurveyAnswer;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;



class SurveyController extends AbstractController
{
    #[Route('/survey/submit', name: 'app_survey_submit', methods: ['POST'])]
    public function sendForm(Request $request, SurveyRepository $surveyRepository, EntityManagerInterface $manager)
    {
        $survey = $surveyRepository->find($request->request->get('surveyId'));
        $optionId = $request->request->get('surveyOption');
        $check = true;
        $surveyName = 'survey'.$survey->getId();


        if ($request->cookies->get($surveyName)){
            $this->addFlash('surveyError', 'Erreur: Vous avez déjà voté à ce sondage.');
            $check = false;
        }

        if ($optionId === null){
            $this->addFlash('surveyError', 'Erreur: Vous ne pouvez pas envoyer un formulaire vide.');
            $check = false;
        }

        if ($check){
            $surveyAnswer = new SurveyAnswer();
            $surveyAnswer->setAnswer($optionId);
            $surveyAnswer->setSurvey($survey);
            $surveyAnswer->setSentAt();
            $cookie = new Cookie( 
                $surveyName,                             // name
                true,                                    // content
                time() + 36000,                          // expiration date
            );

            $response = new Response();
            $response->headers->setCookie($cookie);
            $response->send();

            $manager->persist($surveyAnswer);
            $manager->flush();
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
    
}
