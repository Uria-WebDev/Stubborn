<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    // Route pour chaque produits
    #[Route('/product/{id}', name: 'app_product')]
    public function show(Product $product): Response
    {
        return $this->render('product/index.html.twig', [
            'product' => $product,
        ]);
    }
}
