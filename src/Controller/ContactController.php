<?php

namespace App\Controller;
use App\Entity\Contact;
use DateTime;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setSentAt( new DateTime);
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/show.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
