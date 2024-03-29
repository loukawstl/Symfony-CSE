<?php

namespace App\Controller;

use App\Entity\Partnership;
use App\Entity\File;
use App\Entity\Survey;
use App\Entity\SurveyOption;
use App\Form\Admin\LimitedOfferType;
use App\Repository\PartnershipRepository;
use App\Repository\FileRepository;
use App\Repository\SurveyOptionRepository;
use App\Repository\SurveyRepository;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SidenavController extends AbstractController
{

    public function __construct(private PartnershipRepository $partnershipRepository, private SurveyRepository $surveyRepository, private SurveyOptionRepository $surveyOptionRepository)
    {
        $this->partnershipRepository = $partnershipRepository;
        $this->surveyRepository = $surveyRepository;
        $this->surveyOptionRepository = $surveyOptionRepository;
    }

    public function show($nbPartnerships = 3): Response
    {
        $activatedSurvey = $this->surveyRepository->findActivatedSurvey();
        $activatedSurveyOptions = $activatedSurvey->getSurveyOptions();
        $partnerships = $this->partnershipRepository->findRandomPartnerships($nbPartnerships);

        return $this->render('sidebar_left/show.html.twig', [
            'partnerships' => $partnerships,
            'survey' => $activatedSurvey,
            'surveyOptions' => $activatedSurveyOptions,
        ]);
    }
    
}
