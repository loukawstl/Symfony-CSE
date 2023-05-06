<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(LoginType::class);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            // Validation de l'utilisateur et du mot de passe
            $isValid = $this->validateUser($formData['username'], $formData['password']);
            if (!$isValid) {
                dump($formData['username']);
                dump($formData['password']);
                $this->addFlash('error', 'Nom d\'utilisateur ou mot de passe invalide.');
                return $this->redirectToRoute('login');
            }
            
            $session->start();
            $session->set('connected', 'true');

            // Rediriger l'utilisateur après la connexion réussie
            return $this->redirectToRoute('app_offer_index');
        }

        // Affichage du formulaire de connexion
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Valide l'utilisateur et son mot de passe.
     */
    private function validateUser(string $username, string $password): bool
    {
        // Recherche de l'utilisateur dans la base de données
        $userRepository = $this->entityManager->getRepository(Admin::class);
        $user = $userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            return false;
        }

        // Vérification du mot de passe
        return password_verify($password, $user->getPassword());
    }
}
