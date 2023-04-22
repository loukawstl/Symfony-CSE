<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Entity\File;
use App\Entity\NewsletterSubscriber;
use App\Form\Admin\OfferType;
use App\Repository\OfferRepository;
use App\Repository\NewsletterSubscriberRepository;
use App\Repository\FileRepository;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/admin/gestion-offres')]
class OfferController extends AbstractController
{

    #[Route('/', name: 'app_offer_index')]
    public function index(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $offersRequest = $offerRepository->findAll();

        $offers = $paginator->paginate(
            $offersRequest,
            $request->query->getInt('page', 1),
            10
        );

        if ($request->isXmlHttpRequest()) {
            return $tableManager->prepareData($request);
        }

        return $this->render('admin/offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/ajouter', name: 'app_offer_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, NewsletterSubscriberRepository $newsletterRepository, MailerInterface $mailer): Response
    {
        $offer = new Offer();
        $offer->setDateStart(new \DateTime());
        $offer->setDateEnd(new \DateTime());
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('files')->getData();
            $check = true;

            if($offer->getDateStart()> $offer->getDateEnd()){
                $this->addFlash('error', 'La date de début d\'une offre ne peut pas être après sa date de fin!');
                $check = false;
            }

            if (count($images)>4){
                $this->addFlash('error', 'Une offre limité ne peut pas avoir plus de 4 images.');
                $check = false;
            }

            if (count($images)<1){
                $this->addFlash('error', 'Une offre doit avoir au moins 1 image.');
                $check = false;
            }

            foreach ($images as $image) {
                $extension = $image->guessExtension();
                if ('png' !== $extension && 'jpg' !== $extension && 'jpeg' !== $extension && 'webp' !== $extension && 'svg' !== $extension) {
                    $this->addFlash('error', 'Format d\'image incorrect.');
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
                try {
                    $manager->flush();
                    $this->addFlash('success', 'L\'offre a bien été créée.');
                } catch (DoctrineDBALException $e) {
                    $this->addFlash('success', 'Erreur lors de la création de l\'offre.');
                }

                try {
                    $newsletterSubscribers = $newsletterRepository->findAll();
                    $mailLink = "http://127.0.0.1:8000/billeterie";
                    $mailFrom = "mailtrap@example.com";
                    $mailSubject = "Nouvelle Offre Limitée : " . $offer->getName();
                    $mailMessage = "Offre valable du " . strftime('%d %B %Y à %H:%M:%S',$offer->getDateStart()->getTimestamp()) . " au " . strftime('%d %B %Y à %H:%M:%S',$offer->getDateEnd()->getTimestamp()) . "\n\n" . $offer->getText() . "\n" .  $offer->getTariff() . "\n\n" . "Accéder à l'offre : " . $mailLink;
                    $mailHeaders = "From:" . $mailFrom . "\r\nContent-Type: text/plain; charset=utf-8\r\n";
                    foreach ($newsletterSubscribers as $newsletterSubscriber){
                    if ($newsletterSubscriber->isChecked() == true){
                        $mailTo = $newsletterSubscriber->getEmail();
                        $email = (new Email())
                            ->from($mailFrom)
                            ->to($mailTo)
                            ->subject($mailSubject)
                            ->text($mailMessage)
                        ;
                        $files = $offer->GetFiles();
                        foreach($files as $file){
                            $email->attachFromPath($file->getFilePath());
                        }

                        $mailer->send($email);

                    }
                }
                } catch (DoctrineDBALException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'envoie d\'emails.');
                }

                return $this->redirectToRoute('app_offer_index');
            }
        }

        return $this->render('admin/offer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_offer_modify', methods: ['GET', 'POST'])]
    public function edit(OfferRepository $offerRepository, Offer $offer, EntityManagerInterface $manager, Request $request): Response
    {

        if (null === $offer) {
            return $this->redirectToRoute('app_offer_modify', [
            ]);
        }

        $form = $this->createForm(OfferType::class, $offer, [
            'on_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $images = $form->get('files')->getData();
                $check = true;

                if($offer->getDateStart()> $offer->getDateEnd()){
                    $this->addFlash('error', 'La date de début d\'une offre ne peut pas être après sa date de fin!');
                    $check = false;
                }
    
                if (count($images)+count($offer->getFiles())>4){
                    $this->addFlash('error', 'Une offre ne peut pas avoir plus de 4 images.');
                    $check = false;
                }
    
                if (count($offer->getFiles()) < 1 && count($images) < 1){
                    $this->addFlash('error', 'Une offre doit avoir au moins 1 image.');
                    $check = false;
                }

                foreach ($images as $image) {
                    $extension = $image->guessExtension();
                    if ('png' !== $extension && 'jpg' !== $extension && 'jpeg' !== $extension && 'webp' !== $extension && 'svg' !== $extension) {
                        $this->addFlash('error', 'Format d\'image incorrect.');
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
                    try {
                        $manager->flush();
                        $this->addFlash('success', 'L\'offre a bien été mise à jour.');
                    } catch (DoctrineDBALException $e) {
                        $this->addFlash('error', 'Erreur lors de la modification de l\'offre.');
                    }

                    return $this->render('admin/offer/edit.html.twig', [
                        'form' => $form->createView(),
                        'offer' => $offer,
                    ]);
                }
            }
        }

        return $this->render('admin/offer/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
        
    }

    #[Route('/modifier/{id}', name: 'app_offer_delete', methods: ['DELETE'])]
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

            $this->addFlash('success', 'L\'offre a bien été supprimée.');

            return $this->redirectToRoute('app_offer_index');
        }

        return $this->redirectToRoute('app_offer_index');
    }
    
    #[Route('/modifier/{id}/supression', name: 'app_offer_image_delete', methods: ['DELETE'])]
    public function deleteImage(Offer $offer, Request $request, EntityManagerInterface $manager, FileRepository $fileRepository): Response
    {
        $check = true;
        $submittedToken = $request->request->get('token');
        $imageId = $request->request->get('id');

        if (!$this->isCsrfTokenValid('delete-image', $submittedToken)) {
            $this->addFlash('error', 'Suppression impossible : Token CSRF invalide.');
            $check = false;
        }

        if (null === ($file = $fileRepository->find($imageId))) {
            $this->addFlash('error', 'Suppression impossible : L\'image n\'a pas été trouvée.');
            $check = false;
        }

        if (count($offer->getFiles()) === 1){
            $this->addFlash('error', 'Suppression impossible : Une offre doit avoir au moins 1 image.');
            $check = false;
        }

        if ($check) {
            if(file_exists($file->getFilePath())){
                unlink($file->getFilePath());
            }
            $manager->remove($file);
            try {
                $manager->flush();
                $this->addFlash('success', 'L\'image a bien été supprimée.');
            } catch (DoctrineDBALException $e) {
                $this->addFlash('error', 'Erreur lors de la supression de l\'image.');
            }

            return $this->redirectToRoute('app_offer_modify', [
                'id' => $offer->getId(),
            ]);
        }

        return $this->redirectToRoute('app_offer_modify', [
            'id' => $offer->getId(),
        ]);
    }
}
