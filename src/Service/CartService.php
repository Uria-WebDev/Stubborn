<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProductRepository;

class CartService
{
    private $session;
    private $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;
    }

    // Ajout d'un produit au panier
    public function add(int $productId, string $size)
    {
        $cart = $this->session->get('cart', []);

        $key = $productId . '-' . $size;

        $cart[$key] = [
            'product_id' => $productId,
            'size' => $size
        ];

        $this->session->set('cart', $cart);
    }

    // Recuperation des produits du panier
    public function getCart()
    {
        $cart = $this->session->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $item) {

            $product = $this->productRepository->find($item['product_id']);

            $items[] = [
                'product' => $product,
                'size' => $item['size'],
                'price' => $product->getPrice(),
            ];

            $total += $product->getPrice();
        }

        return ['items' => $items, 'total' => $total];
    }

    // Suppression d'un produit du panier
    public function remove(string $key)
    {
        $cart = $this->session->get('cart', []);
        
        if (isset($cart[$key])) {
            unset($cart[$key]);
        }

        $this->session->set('cart', $cart);
    }
}