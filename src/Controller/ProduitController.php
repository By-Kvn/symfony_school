<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitForm;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Notification;
use App\Service\NotificationService;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/produit')]
final class ProduitController extends AbstractController
{
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'user' => $this->getUser(),

        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitForm::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

#[IsGranted('ROLE_USER')]
#[Route('/produit/{id}/acheter', name: 'app_produit_acheter', methods: ['POST'])]
public function acheter(
    Request $request,
    Produit $produit,
    EntityManagerInterface $em,
    NotificationService $notificationService
): RedirectResponse {
    $user = $this->getUser();
    // Validation du token CSRF
    if (!$this->isCsrfTokenValid('acheter' . $produit->getId(), $request->request->get('_token'))) {
        $this->addFlash('danger', 'Jeton CSRF invalide.');
        return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()]);
    }
    $redirectRoute = null;
    $redirectParams = [];

    if (!$user instanceof \App\Entity\User) {
        $this->addFlash('danger', 'Utilisateur non valide.');
        $redirectRoute = 'app_produit_show';
        $redirectParams = ['id' => $produit->getId()];
    } elseif (!$user->isActif()) {
        $this->addFlash('danger', 'Votre compte est désactivé. Vous ne pouvez pas effectuer d\'achat.');
        $redirectRoute = 'app_produit_show';
        $redirectParams = ['id' => $produit->getId()];
    } elseif ($user->getPoints() < $produit->getPrix()) {
        $this->addFlash('danger', 'Vous n\'avez pas assez de points pour acheter ce produit.');
        $redirectRoute = 'app_produit_show';
        $redirectParams = ['id' => $produit->getId()];
    } else {
        // ✅ Déduction des points
        $user->setPoints($user->getPoints() - $produit->getPrix());

        // ✅ Utilisation du NotificationService
        $label = sprintf(
            'Achat : %s a acheté %s pour %d points le %s',
            $user->getNom(),
            $produit->getNom(),
            $produit->getPrix(),
            (new \DateTime())->format('d/m/Y H:i')
        );
        $notificationService->create($label, $user);

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Achat effectué avec succès !');
        $redirectRoute = 'app_produit_index';
    }

    return $this->redirectToRoute($redirectRoute, $redirectParams);
}


}
