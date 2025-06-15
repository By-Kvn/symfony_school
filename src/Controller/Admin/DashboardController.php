<?php
// src/Controller/Admin/DashboardController.php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Doctrine\ORM\EntityManagerInterface;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/dashboard', name: 'app_admin_dashboard')]
class DashboardController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(): Response
    {
        $users = $this->entityManager->getRepository(\App\Entity\User::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'user' => $this->getUser(),
            'points' => $this->entityManager->getRepository(\App\Entity\User::class)->findBy(['actif' => true]),
            'notifications' => $this->entityManager->getRepository(\App\Entity\Notification::class)->findBy(['user' => $this->getUser()]),
            'produits' => $this->entityManager->getRepository(\App\Entity\Produit::class)->findAll(),
        ]);
    }
}
