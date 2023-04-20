<?php

namespace App\Controller\Admin;

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

class AdminSideNavController extends AbstractController
{
    
    public function show(): Response
    {
        return $this->render('admin/admin_sidebar_left/show.html.twig', [
        ]);
    }
    
}
