<?php
namespace App\Controller;

use App\Repository\PartnershipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class PartnershipController extends AbstractController
{

    #[Route('/partenariat', name: 'app_partnership', methods: ['GET'])]
    public function partnership(PartnershipRepository $partnershipRepository): Response
    {
        
        return $this->render('home_page/partnership.html.twig', [
            'partnerships' => $partnershipRepository->findAll(),
        ]);
    }

}
