<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use App\Service\CartService;
use App\Repository\ProductRepository;
use App\Entity\Product;

class CartServiceTest extends TestCase
{
    private CartService $cartService;
    private Session $session;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());

        $request = new Request();
        $request->setSession($this->session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $product = new Product();
        $product->setName('T-shirt');
        $product->setPrice(20.0);

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('find')->willReturn($product);

        $this->cartService = new CartService($requestStack, $productRepository);
    }

    /**
    * @covers \App\Service\CartService::add
    */
    // Test d'ajout de produit au panier
    public function testAddProductToCart(): void
    {
        $this->cartService->add(1, 'M');

        $cart = $this->session->get('cart');
        $this->assertCount(1, $cart);
        $this->assertArrayHasKey('1-M', $cart);
    }

    /**
    * @covers \App\Service\CartService::remove
    */
    // Test de suppression d'un produit du panier
    public function testRemoveProductFromCart(): void
    {
        $this->cartService->add(1, 'M');
        $this->cartService->remove('1-M');

        $cart = $this->session->get('cart');
        $this->assertEmpty($cart);
    }

    /**
    * @covers \App\Service\CartService::getCart
    */
    // Test du montant total du panier
    public function testGetCartReturnsTotal(): void
    {
        $this->cartService->add(1, 'M');

        $cart = $this->cartService->getCart();

        $this->assertEquals(20, $cart['total']);
        $this->assertCount(1, $cart['items']);
    }
}