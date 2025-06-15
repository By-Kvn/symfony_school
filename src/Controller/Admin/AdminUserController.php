<?php
// src/Controller/AdminUserController.php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user/{id}/toggle-actif', name: 'admin_user_toggle_actif', methods: ['POST'])]
    public function toggleActif(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        CsrfTokenManagerInterface $csrfTokenManager
    ): RedirectResponse {
        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('admin_dashboard'); // adapte cette route
        }

        // Vérification du token CSRF
        $token = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new \Symfony\Component\Security\Csrf\CsrfToken('toggle_actif' . $id, $token))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Inverser l’état actif
        $user->setActif(!$user->getActif());
        $em->flush();

        $this->addFlash('success', sprintf(
            'L\'utilisateur %s a été %s.',
            $user->getEmail(),
            $user->getActif() ? 'activé' : 'désactivé'
        ));

        return $this->redirectToRoute('admin_dashboard');
    }
}
