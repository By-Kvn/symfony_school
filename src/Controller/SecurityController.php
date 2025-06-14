<?php
// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // Si l'utilisateur est déjà connecté, rediriger vers /produit
        if ($this->getUser()) {
            return $this->redirectToRoute('app_produit_index'); // Assurez-vous que cette route existe
        }

        // Récupère l'erreur s’il y en a
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier username saisi par l’utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide : c'est Symfony qui gère la déconnexion automatiquement.
    }
}

