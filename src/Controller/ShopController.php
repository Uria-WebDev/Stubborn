<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $range = $request->query->get('price_range');

        $repo = $em->getRepository(Product::class);

        // Tri par tranche de prix
        if (!$range) {
            $products = $repo->findAll();
        } else {
            $ranges = [
                '10-30' => [10, 30],
                '30-35' => [30, 35],
                '35-50' => [35, 50],
            ];

            [$min, $max] = $ranges[$range];

            $products = $repo->createQueryBuilder('p')
                ->where('p.price >= :min')
                ->andWhere('p.price <= :max')
                ->setParameter('min', $min)
                ->setParameter('max', $max)
                ->getQuery()
                ->getResult();
        }

        return $this->render('shop/index.html.twig', [
            'products' => $products,
        ]);
    }
}
