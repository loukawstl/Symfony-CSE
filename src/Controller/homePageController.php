<?php
namespace App\Controller;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class homePageController extends AbstractController
{

#[Route('/accueil', name: 'app_home_page', methods: ['GET'])]
    public function show(OfferRepository $offerRepository): Response
    {

        $nbOffers = 3;
        $offers = $offerRepository->findNbLimitedOffers($nbOffers);

        return $this->render('homePage/homePage.html.twig', [
            'limitedOffers' => $limitedOffers,
        ]);
    } 
}