<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use App\Form\Survey\SurveyType;
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
    
}
