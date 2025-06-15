<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Crée et persiste une notification pour un utilisateur.
     */
    public function create(string $label, User $user): void
    {
        $notification = new Notification();
        $notification->setLabel($label);
        $notification->setUser($user);
        $this->em->persist($notification);
    }

    /**
     * Crée et flush une notification immédiatement.
     */
    public function notify(string $label, User $user): void
    {
        $this->create($label, $user);
        $this->em->flush();
    }
}
