<?php

namespace App\Controller;

use App\Entity\StaticContent;
use App\Repository\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/a_propos_de_nous', name: 'app_a_propos')]
class AProposController extends AbstractController
{

    #[Route('/', name: 'app_a_propos', methods: ['GET'])]
    public function aProposDeNous(StaticContentRepository $staticContentRepository): Response
    {
        return $this->render('a_propos_de_nous/index.html.twig', [
            'content_list' => $staticContentRepository->findBy(['page' => 'a-propos-de-nous']),
        ]);
    }
}
