<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProduitTypeForm;
use App\Entity\Produit;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit/new', name: 'produit_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitTypeForm::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
