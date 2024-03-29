<?php

namespace App\Controller\Admin;

use App\Entity\StaticContent;
use App\Form\StaticContentType;
use App\Repository\StaticContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminRedirect;

#[Route('/admin/gestion-textes')]
class StaticContentController extends AbstractController
{
    #[Route('/', name: 'app_static_content_index', methods: ['GET'])]
    public function index(StaticContentRepository $staticContentRepository, SessionInterface $session, AdminRedirect $adminRedirect): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        return $this->render('admin/static_content/index.html.twig', [
            'static_contents' => $staticContentRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_static_content_show', methods: ['GET'])]
    public function show(StaticContent $staticContent): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        return $this->render('admin/static_content/show.html.twig', [
            'static_content' => $staticContent,
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_static_content_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StaticContent $staticContent, StaticContentRepository $staticContentRepository, SessionInterface $session, AdminRedirect $adminRedirect): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(StaticContentType::class, $staticContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $staticContentRepository->save($staticContent, true);

            return $this->redirectToRoute('app_static_content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/static_content/edit.html.twig', [
            'static_content' => $staticContent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_static_content_delete', methods: ['POST'])]
    public function delete(Request $request, StaticContent $staticContent, StaticContentRepository $staticContentRepository, SessionInterface $session, AdminRedirect $adminRedirect): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        if ($this->isCsrfTokenValid('delete'.$staticContent->getId(), $request->request->get('_token'))) {
            $staticContentRepository->remove($staticContent, true);
        }

        return $this->redirectToRoute('app_static_content_index', [], Response::HTTP_SEE_OTHER);
    }
}
