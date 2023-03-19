<?php

namespace App\Controller;

use App\Entity\StaticContent;
use App\Form\StaticContentType;
use App\Repository\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/static/content')]
class StaticContentController extends AbstractController
{
    #[Route('/', name: 'app_static_content_index', methods: ['GET'])]
    public function index(StaticContentRepository $staticContentRepository): Response
    {
        return $this->render('static_content/index.html.twig', [
            'static_contents' => $staticContentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_static_content_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StaticContentRepository $staticContentRepository): Response
    {
        $staticContent = new StaticContent();
        $form = $this->createForm(StaticContentType::class, $staticContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $staticContentRepository->save($staticContent, true);

            return $this->redirectToRoute('app_static_content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('static_content/new.html.twig', [
            'static_content' => $staticContent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_static_content_show', methods: ['GET'])]
    public function show(StaticContent $staticContent): Response
    {
        return $this->render('static_content/show.html.twig', [
            'static_content' => $staticContent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_static_content_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StaticContent $staticContent, StaticContentRepository $staticContentRepository): Response
    {
        $form = $this->createForm(StaticContentType::class, $staticContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $staticContentRepository->save($staticContent, true);

            return $this->redirectToRoute('app_static_content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('static_content/edit.html.twig', [
            'static_content' => $staticContent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_static_content_delete', methods: ['POST'])]
    public function delete(Request $request, StaticContent $staticContent, StaticContentRepository $staticContentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staticContent->getId(), $request->request->get('_token'))) {
            $staticContentRepository->remove($staticContent, true);
        }

        return $this->redirectToRoute('app_static_content_index', [], Response::HTTP_SEE_OTHER);
    }
}
