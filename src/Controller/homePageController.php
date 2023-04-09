<?php
namespace App\Controller;

use App\Entity\Offer;
use App\Entity\StaticContent;
use App\Repository\OfferRepository;
use App\Repository\PartnershipRepository;
use App\Repository\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class HomePageController extends AbstractController
{

    #[Route('/accueil', name: 'app_home_page', methods: ['GET'])]
    public function homePage(OfferRepository $offerRepository, StaticContentRepository $staticContentRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $staticContent = $staticContentRepository->findTextHomePage();
        $textHomePage = "";

        $offersRequest = $offerRepository->findLimitedOfferByDate();

        $offers = $paginator->paginate(
            $offersRequest,
            $request->query->getInt('page', 1),
            3
        );

        if ($staticContent !== null){
            $textHomePage = $staticContent->getContent();
        }
        
        return $this->render('home_page/show.html.twig', [
            'offers' => $offers,
            'textHomePage' => $textHomePage,
        ]);
    } 

    #[Route('/partenariat', name: 'app_partnership', methods: ['GET'])]
    public function partnership(PartnershipRepository $partnershipRepository): Response
    {
        
        return $this->render('home_page/partnership.html.twig', [
            'partnerships' => $partnershipRepository->findAll(),
        ]);
    }

}
