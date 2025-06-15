<?php

namespace App\Controller\Admin;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminNotificationController extends AbstractController
{
    #[Route('/admin/notifications', name: 'admin_notifications_index')]
    public function index(NotificationRepository $notificationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $notifications = $notificationRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/notifications/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
