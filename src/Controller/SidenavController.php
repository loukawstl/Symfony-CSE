<?php

namespace App\Controller;

use App\Entity\Partnership;
use App\Entity\File;
use App\Form\Admin\LimitedOfferType;
use App\Repository\PartnershipRepository;
use App\Repository\FileRepository;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SidenavController extends AbstractController
{

    private $partnershipRepository;

    public function __construct(PartnershipRepository $partnershipRepository)
    {
        $this->partnershipRepository = $partnershipRepository;
    }
    
    public function showRandomPartnerships($nb = 3): Response
    {
        $partnerships = $this->partnershipRepository->findRandomPartnerships($nb);

        return $this->render('sidebar_left/sidebar.html.twig', [
            'partnerships' => $partnerships 
        ]);
    }

}
