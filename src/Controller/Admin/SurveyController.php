<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use App\Form\Admin\SurveyType;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\AdminRedirect;

#[Route('/admin/gestion-questionnaires')]
class SurveyController extends AbstractController
{

    #[Route('/', name: 'app_survey_index')]
    public function index(SurveyRepository $surveyRepository, Request $request, PaginatorInterface $paginator, AdminRedirect $adminRedirect, SessionInterface $session): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        $surveysRequest = $surveyRepository->findAllByActivated();

        $surveys = $paginator->paginate(
            $surveysRequest,
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            return $tableManager->prepareData($request);
        }

        return $this->render('admin/survey/index.html.twig', [
            'surveys' => $surveys,
        ]);
    }

    #[Route('/ajouter', name: 'app_survey_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, SessionInterface $session, AdminRedirect $adminRedirect, SurveyRepository $surveyRepository): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        $survey = new Survey();
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $check = true;
            $surveyOptions = $survey->getSurveyOptions();
            
            if (count($surveyOptions)<2){
                $this->addFlash('error', 'Un questionnaire doit avoir au moins 2 options.');
                $check = false;
            }

            if (count($surveyOptions)>6){
                $this->addFlash('error', 'Un questionnaire ne peut pas avoir plus de 6 options.');
                $check = false;
            }

            if ($check){
                foreach ($surveyOptions as $surveyOption) {
                    $surveyOption->setSurvey($survey);
                }
    
                $surveyRepository->deactivateAllSurveys();
                $survey->setActivated(true);
                $manager->persist($survey);
                try {
                    $manager->flush();
                    $this->addFlash('success', 'Le questionnaire a bien été créé.');
                } catch (DoctrineDBALException $e) {
                }
    
                return $this->redirectToRoute('app_survey_index');
            }
        
        }

        return $this->render('admin/survey/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
