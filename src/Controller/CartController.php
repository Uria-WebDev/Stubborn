<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    // Route d'ajout d'un produit au panier
    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function add(Request $request, CartService $cartService): Response
    {
        $productId = $request->request->get('product_id');
        $size = $request->request->get('size');

        if (!$size) {
            $this->addFlash('error', 'Veuillez sélectionner une taille.');
            return $this->redirectToRoute('app_product', ['id' => $productId]);
        }

        $cartService->add($productId, $size);

        return $this->redirectToRoute('cart_show');
    }

    // Route de suppression d'un produit du panier
    #[Route('/cart/remove/{key}', name: 'cart_remove')]
    public function remove(string $key, CartService $cartService): Response
    {
        $cartService->remove($key);
        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart', name: 'cart_show')]
    public function show(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart()
        ]);
    }
}