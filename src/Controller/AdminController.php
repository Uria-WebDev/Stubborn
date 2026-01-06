<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    // Route de la page admin
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'products' => $products,
        ]);
    }

    // Route de création d'un nouveau produit
    #[Route('/admin/product/new', name: 'admin_product_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit créé !');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route de modification d'un produit
    #[Route('/admin/product/{id}/edit', name: 'admin_product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Produit modifié !');
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/product_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route de suppression d'un produit
    #[Route('/admin/product/{id}/delete', name: 'admin_product_delete', methods: ['POST'])]
    public function delete(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Produit supprimé !');
        }

        return $this->redirectToRoute('app_admin');
    }
}