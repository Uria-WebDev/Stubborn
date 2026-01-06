<?php

namespace App\Tests\Service;

use App\Service\StripeService;
use PHPUnit\Framework\TestCase;
use Stripe\Checkout\Session;

class StripeServiceTest extends TestCase
{
    /**
    * @covers \App\Service\StripeService::createCheckoutSession
    */
    // Test du service Stripe
    public function testCreateCheckoutSession(): void
    {
        $product = $this->createMock(\App\Entity\Product::class);
        $product->method('getName')->willReturn('T-shirt');
        $product->method('getPrice')->willReturn(20.0);

        $cart = [
            'items' => [
                ['product' => $product]
            ]
        ];

        $stripeService = $this->getMockBuilder(StripeService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createCheckoutSession'])
            ->getMock();

        $stripeService->expects($this->once())
            ->method('createCheckoutSession')
            ->willReturn((object)['url' => 'https://stripe.test/checkout']);

        $session = $stripeService->createCheckoutSession($cart);

        $this->assertEquals('https://stripe.test/checkout', $session->url);
    }
}