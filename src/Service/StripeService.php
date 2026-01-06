<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
    // Configuration du service Stripe
    public function __construct(string $stripeSecretKey)
    {
        Stripe::setApiKey($stripeSecretKey);
    }

    public function createCheckoutSession(array $cart)
    {
        $lineItems = [];

        foreach ($cart['items'] as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['product']->getName(),
                    ],
                    'unit_amount' => (int) ($item['product']->getPrice() * 100),
                ],
                'quantity' => 1,
            ];
        }

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/payment/success',
            'cancel_url' => 'http://localhost:8000/payment/cancel',
        ]);
    }
}