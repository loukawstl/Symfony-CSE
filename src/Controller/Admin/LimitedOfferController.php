<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Entity\File;
use App\Form\Admin\LimitedOfferType;
use App\Repository\OfferRepository;
use App\Repository\FileRepository;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Admin/GestionOffresLimités')]
class LimitedOfferController extends AbstractController
{

    #[Route('/', name: 'app_limited_offer_index')]
    public function index(OfferRepository $offerRepository, Request $request): Response
    {
        $offers = $offerRepository->findAllLimitedOffers();

        if ($request->isXmlHttpRequest()) {
            return $tableManager->prepareData($request);
        }

        return $this->render('admin/limited_offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/Ajouter', name: 'app_limited_offer_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $offer = new Offer();
        $offer->setDateStart(new \DateTime());
        $offer->setDateEnd(new \DateTime());
        $form = $this->createForm(LimitedOfferType::class, $offer);
        $errorManager = "";

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('files')->getData();
            $check = true;

            if($offer->getDateStart()> $offer->getDateEnd()){
                $errorManager .= "La date de début d'une offre ne peut pas être après sa date de fin! <br>";
                $check = false;
            }

            if (count($images)>4){
                $errorManager .= "Une offre limité ne peut pas avoir plus de 4 images <br>";
                $check = false;
            }

            if (count($images)<1){
                $errorManager .= "Une offre doit avoir au moins 1 image <br>";
                $check = false;
            }

            foreach ($images as $image) {
                $extension = $image->guessExtension();
                if ('png' !== $extension && 'jpg' !== $extension && 'jpeg' !== $extension && 'webp' !== $extension && 'svg' !== $extension) {
                    $errorManager .= "Format d\'image incorrect <br>";
                    $check = false;
                }
            }

            if ($check) {
                foreach ($images as $image) {
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
                    $offer->addFile($file);
                }
                $offer = $form->getData();
                $offer->setTypeOfOffer("limité");

                $manager->persist($offer);
                $manager->flush();

                return $this->redirectToRoute('app_limited_offer_index');
            }
        }

        return $this->render('admin/limited_offer/new.html.twig', [
            'form' => $form->createView(),
            'errorManager' => $errorManager,
        ]);
    }

    #[Route('/Modifier/{id}', name: 'app_limited_offer_modify', methods: ['GET', 'POST'])]
    public function edit(OfferRepository $offerRepository, Offer $offer, EntityManagerInterface $manager, Request $request): Response
    {

        if ((null === $offer)||($offer->getTypeOfOffer() != "limité")) {
            return $this->redirectToRoute('app_limited_offer_modify', [
            ]);
        }

        $form = $this->createForm(LimitedOfferType::class, $offer, [
            'on_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $images = $form->get('files')->getData();
                $check = true;

                if($offer->getDateStart()> $offer->getDateEnd()){
                    $errorManager .= "La date de début d'une offre ne peut pas être après sa date de fin! <br>";
                    $check = false;
                }
    
                if (count($images)+count($offer->getFiles())>4){
                    $errorManager .= "Une offre ne peut pas avoir plus de 4 images <br>";
                    $check = false;
                }
    
                if (count($offer->getFiles()) < 1 && count($images) < 1){
                    $errorManager .= "Une offre doit avoir au moins 1 image <br>";
                    $check = false;
                }

                foreach ($images as $image) {
                    $extension = $image->guessExtension();
                    if ('png' !== $extension && 'jpg' !== $extension && 'jpeg' !== $extension && 'webp' !== $extension && 'svg' !== $extension) {
                        $errorManager .= "Format d\'image incorrect <br>";
                        $check = false;
                    }
                }

                if ($check) {
                    foreach ($images as $image) {
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
                        $offer->addFile($file);
                    }

                    $offer = $form->getData();

                    $manager->persist($offer);
                    $manager->flush();

                    return $this->render('admin/limited_offer/edit.html.twig', [
                        'form' => $form->createView(),
                        'offer' => $offer,
                        'successMessage' => "L'offre a bien été modifiée"
                    ]);
                }
            }
        }

        return $this->render('admin/limited_offer/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
        
    }

    #[Route('/Modifier/{id}', name: 'app_limited_offer_delete', methods: ['DELETE'])]
    public function delete(OfferRepository $offerRepository, Offer $offer, EntityManagerInterface $manager, Request $request): Response
    {
        
        $check = true;
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete-offer', $submittedToken)) {
            $this->addFlash('error', 'Suppression impossible : Token CSRF invalide.');
            $check = false;
        }

        if ($check) {
            $manager->remove($offer);
            try {
                $manager->flush();
            } catch (DoctrineDBALException $e) {
            }

            return $this->redirectToRoute('app_limited_offer_index');
        }

        return $this->redirectToRoute('app_limited_offer_index');
    }
    
    #[Route('/Modifier/{id}/Supression', name: 'app_limited_offer_image_delete', methods: ['DELETE'])]
    public function deleteImage(Offer $offer, Request $request, EntityManagerInterface $manager, FileRepository $fileRepository): Response
    {
        $check = true;
        $submittedToken = $request->request->get('token');
        $imageId = $request->request->get('id');
        $errorManager = "";

        if (!$this->isCsrfTokenValid('delete-image', $submittedToken)) {
            $errorManager .= "Suppression impossible : Token CSRF invalide <br>";
            $check = false;
        }

        if (null === ($file = $fileRepository->find($imageId))) {
            $errorManager .= "Suppression impossible : L\'image n\'a pas été trouvée <br>";
            $check = false;
        }

        if ($check) {
            if(file_exists($file->getFilePath())){
                unlink($file->getFilePath());
            }
            $manager->remove($file);
            try {
                $manager->flush();
            } catch (DoctrineDBALException $e) {
                $errorManager .= "Erreur lors de la supression de l\'image <br>";
            }

            return $this->redirectToRoute('app_limited_offer_modify', [
                'id' => $offer->getId(),
            ]);
        }

        return $this->redirectToRoute('app_limited_offer_modify', [
            'id' => $offer->getId(),
            'errorManager' => $errorManager,
        ]);
    }
}
