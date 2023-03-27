<?php
namespace App\Controller;
use App\Entity\Offer;
use App\Entity\StaticContent;
use App\Repository\OfferRepository;
use App\Repository\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class homePageController extends AbstractController
{

#[Route('/accueil', name: 'app_home_page', methods: ['GET'])]
    public function show(OfferRepository $offerRepository, StaticContentRepository $staticContentRepository): Response
    {

        $nbOffers = 3;
        $offers = $offerRepository->findNbLimitedOffers($nbOffers);
        $staticContent = $staticContentRepository->findTextHomePage();

        return $this->render('homePage/homePage.html.twig', [
            'offers' => $offers,
            'textHomePage' => $staticContent->getContent(),
        ]);
    } 
}