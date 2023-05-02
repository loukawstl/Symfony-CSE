<?php
namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class TicketingController extends AbstractController
{

    #[Route('/billeterie', name: 'app_ticketing', methods: ['GET'])]
    public function homePage(OfferRepository $offerRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $offersRequest = $offerRepository->findAllWithNumberOrderPage();

        $offers = $paginator->paginate(
            $offersRequest,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('ticketing/show.html.twig', [
            'offers' => $offers,
        ]);
    } 

}
