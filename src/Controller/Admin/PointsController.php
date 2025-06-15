<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/points')]
class PointsController extends AbstractController
{
    #[Route('/ajouter', name: 'app_admin_ajouter_points', methods: ['POST'])]
    public function ajouterPoints(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        NotificationService $notificationService
    ): RedirectResponse {
        $usersActifs = $userRepository->findBy(['actif' => true]);
        $pointsAjoutes = 1000;

        foreach ($usersActifs as $user) {
            $user->setPoints($user->getPoints() + $pointsAjoutes);
            $em->persist($user);
        }
        $em->flush();

        $label = sprintf("Ajout de %d points à tous les users actifs le %s", $pointsAjoutes, (new \DateTime())->format('d/m/Y H:i'));
        $admin = $this->getUser();
        $notificationService->create($label, $admin);

        $this->addFlash('success', 'Points ajoutés avec succès à tous les users actifs.');

        return $this->redirectToRoute('app_admin_dashboard'); // adapte selon ta route d’accueil admin
    }

    #[Route('/ajouter/{id}', name: 'app_admin_ajouter_points_user', methods: ['POST'])]
public function ajouterPointsUser(
    int $id,
    UserRepository $userRepository,
    EntityManagerInterface $em,
    NotificationService $notificationService
): RedirectResponse {
    $user = $userRepository->find($id);
    if (!$user) {
        $this->addFlash('error', 'Utilisateur non trouvé.');
        return $this->redirectToRoute('app_admin_dashboard');
    }

    $pointsAjoutes = 1000;
    $user->setPoints($user->getPoints() + $pointsAjoutes);
    $em->persist($user);
    $em->flush();

    $label = sprintf("Ajout de %d points à l'utilisateur %s le %s", $pointsAjoutes, $user->getEmail(), (new \DateTime())->format('d/m/Y H:i'));
    $admin = $this->getUser();
    $notificationService->create($label, $admin);

    $this->addFlash('success', 'Points ajoutés avec succès à ' . $user->getEmail());

    return $this->redirectToRoute('app_admin_dashboard');
}
}
