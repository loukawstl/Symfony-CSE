<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Form\Admin\AdminType;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\AdminRedirect;

#[Route('/admin/gestion-administrateurs')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'app_admin_index')]
    public function index(AdminRepository $adminRepository, Request $request, PaginatorInterface $paginator, AdminRedirect $adminRedirect, SessionInterface $session): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        $adminsRequest = $adminRepository->findAll();

        $admins = $paginator->paginate(
            $adminsRequest,
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            return $tableManager->prepareData($request);
        }

        return $this->render('admin/admin/index.html.twig', [
            'admins' => $admins,
        ]);
    }

    #[Route('/ajouter', name: 'app_admin_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, SessionInterface $session, AdminRedirect $adminRedirect): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();
            $admin->setPassword(password_hash($admin->getPassword(), PASSWORD_BCRYPT));

            $manager->persist($admin);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'offre a bien été créée.');
            } catch (DoctrineDBALException $e) {
                $this->addFlash('success', 'Erreur lors de la création de l\'offre.');
            }

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->render('admin/admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_admin_modify', methods: ['GET', 'POST'])]
    public function edit(AdminRepository $adminRepository, Admin $admin, EntityManagerInterface $manager, Request $request, SessionInterface $session, AdminRedirect $adminRedirect): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }

        if (null === $admin) {
            return $this->redirectToRoute('app_admin_modify', [
            ]);
        }

        $form = $this->createForm(AdminType::class, $admin, [
            'on_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $admin = $form->getData();
            $admin->setPassword(password_hash($admin->getPassword(), PASSWORD_BCRYPT));

            $manager->persist($admin);
            try {
                $manager->flush();
                $this->addFlash('success', 'Le compte administrateur a bien été mise à jour.');
            } catch (DoctrineDBALException $e) {
                $this->addFlash('error', 'Erreur lors de la modification du compte administrateur.');
            }

            return $this->render('admin/admin/edit.html.twig', [
                'form' => $form->createView(),
                'admin' => $admin,
            ]);
        }

        return $this->render('admin/admin/edit.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin,
        ]);
        
    }

    #[Route('/modifier/{id}', name: 'app_admin_delete', methods: ['DELETE'])]
    public function delete(AdminRepository $adminRepository, Admin $admin, EntityManagerInterface $manager, Request $request, SessionInterface $session, AdminRedirect $adminRedirect): Response
    {
        if ($adminRedirect->isLogged($session) == false){
            return $this->redirectToRoute('login');
        }
        
        $check = true;
        $submittedToken = $request->request->get('token');
        $admins = $adminRepository->findAll();

        if (!$this->isCsrfTokenValid('delete-admin', $submittedToken)) {
            $this->addFlash('error', 'Suppression impossible : Token CSRF invalide.');
            $check = false;
        }

        if (count($admins)<2) {
            $this->addFlash('error', 'Suppression impossible : Nombre minimum de comptes administrateurs atteint.');
            $check = false;
        }

        if ($check) {
            $manager->remove($admin);
            try {
                $manager->flush();
            } catch (DoctrineDBALException $e) {
            }

            $this->addFlash('success', 'Le compte administrateur a bien été supprimée.');

            return $this->redirectToRoute('app_admin_index');
        }

        return $this->redirectToRoute('app_admin_index');
    }
    
}
