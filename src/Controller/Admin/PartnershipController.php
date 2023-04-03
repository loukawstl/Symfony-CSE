<?php

namespace App\Controller\Admin;

use App\Entity\Partnership;
use App\Entity\File;
use App\Entity\Offer;
use App\Form\PartnershipType;
use App\Repository\PartnershipRepository;
use App\Repository\FileR;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/gestion-partenaires')]
class PartnershipController extends AbstractController
{
    #[Route('/', name: 'app_partnership_index', methods: ['GET'])]
    public function index(PartnershipRepository $partnershipRepository): Response
    {
        return $this->render('admin/partnership/index.html.twig', [
            'partnerships' => $partnershipRepository->findAll(),
        ]);
    }

    #[Route('/ajouter', name: 'app_partnership_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $partnership = new Partnership();
        $form = $this->createForm(PartnershipType::class, $partnership);
        $errorManager = "";

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('file')->getData();
            $check = true;
            $extension = $image->guessExtension();
            if ('png' !== $extension && 'jpg' !== $extension && 'jpeg' !== $extension && 'webp' !== $extension && 'svg' !== $extension) {
                $errorManager .= "Format d\'image incorrect <br>";
                $check = false;
            }

            if ($check) {
                $fileName = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $file = new File();
                $file->setName($fileName);
                $path = $this->getParameter('image_directory').'/'.$fileName;
                $file->setFilePath('uploads/'.$fileName);
                $file->setOriginalName($image->getClientOriginalName());
                $partnership->setFile($file);
                $partnership = $form->getData();
                $manager->persist($partnership);
                $manager->flush();

                return $this->redirectToRoute('app_partnership_index');
            }
        }

        return $this->render('admin/partnership/new.html.twig', [
            'form' => $form->createView(),
            'errorManager' => $errorManager,
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_partnership_edit', methods: ['GET', 'POST'])]
    public function edit(PartnershipRepository $partnershipRepository, Partnership $partnership, EntityManagerInterface $manager, Request $request): Response
    {

        $form = $this->createForm(PartnershipType::class, $partnership, [
            'on_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $image = $form->get('file')->getData();
                $check = true;
                $extension = $image->guessExtension();
                if ('png' !== $extension && 'jpg' !== $extension && 'jpeg' !== $extension && 'webp' !== $extension && 'svg' !== $extension) {
                    $errorManager .= "Format d\'image incorrect <br>";
                    $check = false;
                }

                if ($check) {
                    $fileName = md5(uniqid()).'.'.$image->guessExtension();
                    $image->move(
                        $this->getParameter('image_directory'),
                        $fileName
                    );
                    $file = new File();
                    $file->setName($fileName);
                    $path = $this->getParameter('image_directory').'/'.$fileName;
                    $file->setFilePath('uploads/'.$fileName);
                    $file->setOriginalName($image->getClientOriginalName());
                    $partnership->setFile($file);
                    $partnership = $form->getData();

                    $manager->persist($partnership);
                    $manager->flush();

                    return $this->render('admin/partnership/edit.html.twig', [
                        'form' => $form->createView(),
                        'partnership' => $partnership,
                        'successMessage' => "Le partenaire a bien été modifiée"
                    ]);
                }
            }
        }

        return $this->render('admin/partnership/edit.html.twig', [
            'form' => $form->createView(),
            'partnership' => $partnership,
        ]);
        
    }

    #[Route('/{id}', name: 'app_partnership_delete', methods: ['DELETE'])]
    public function delete(PartnershipRepository $partnershipRepository, Partnership $partnership, EntityManagerInterface $manager, Request $request): Response
    {
        
        $check = true;
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete-partnership', $submittedToken)) {
            $this->addFlash('error', 'Suppression impossible : Token CSRF invalide.');
            $check = false;
        }

        if ($check) {
            $manager->remove($partnership);
            try {
                $manager->flush();
            } catch (DoctrineDBALException $e) {
            }

            return $this->redirectToRoute('app_partnership_index');
        }

        return $this->redirectToRoute('app_partnership_index');
    }
}
