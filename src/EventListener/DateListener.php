<?php
namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Notification;

class DateListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User && !$entity instanceof Produit && !$entity instanceof Notification) {
            return;
        }

        $now = new \DateTimeImmutable();

        if (method_exists($entity, 'setCreatedAt') && null === $entity->getCreatedAt()) {
            $entity->setCreatedAt($now);
        }
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt($now);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User && !$entity instanceof Produit && !$entity instanceof Notification) {
            return;
        }

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
