<?php

namespace App\Controller;

use App\Entity\StaticContent;
use App\Repository\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutUsController extends AbstractController
{

    #[Route('/a-propos', name: 'app_a_propos', methods: ['GET'])]
    public function show(StaticContentRepository $staticContentRepository): Response
    {
        return $this->render('a_propos_de_nous/show.html.twig', [
            'content_list' => $staticContentRepository->findBy(['page' => 'a-propos-de-nous']),
        ]);
    }
}
