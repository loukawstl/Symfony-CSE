<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request)
    {
        // Création du formulaire de connexion
        $form = $this->createForm(LoginType::class);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : traitement de la connexion
        }

        // Affichage du formulaire de connexion
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}