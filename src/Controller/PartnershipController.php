<?php

namespace App\Controller;

use App\Entity\Partnership;
use App\Form\PartnershipType;
use App\Repository\PartnershipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partenaireBack')]
class PartnershipController extends AbstractController
{
    #[Route('/', name: 'app_partnership_index', methods: ['GET'])]
    public function index(PartnershipRepository $partnershipRepository): Response
    {
        return $this->render('partnership/index.html.twig', [
            'partnerships' => $partnershipRepository->findAll(),
        ]);
    }

    #[Route('/ajouter', name: 'app_partnership_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartnershipRepository $partnershipRepository): Response
    {
        $partnership = new Partnership();
        $form = $this->createForm(PartnershipType::class, $partnership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnershipRepository->save($partnership, true);

            return $this->redirectToRoute('app_partnership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partnership/new.html.twig', [
            'partnership' => $partnership,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partnership_show', methods: ['GET'])]
    public function show(Partnership $partnership): Response
    {
        return $this->render('partnership/show.html.twig', [
            'partnership' => $partnership,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_partnership_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Partnership $partnership, PartnershipRepository $partnershipRepository): Response
    {
        $form = $this->createForm(PartnershipType::class, $partnership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnershipRepository->save($partnership, true);

            return $this->redirectToRoute('app_partnership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partnership/edit.html.twig', [
            'partnership' => $partnership,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partnership_delete', methods: ['POST'])]
    public function delete(Request $request, Partnership $partnership, PartnershipRepository $partnershipRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partnership->getId(), $request->request->get('_token'))) {
            $partnershipRepository->remove($partnership, true);
        }

        return $this->redirectToRoute('app_partnership_index', [], Response::HTTP_SEE_OTHER);
    }
}
