<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    // Route de payement via Stripe
    #[Route('/payment/checkout', name: 'payment_checkout')]
    public function checkout(
        CartService $cartService,
        StripeService $stripeService
    ): RedirectResponse {
        $cart = $cartService->getCart();

        if (empty($cart['items'])) {
            return $this->redirectToRoute('app_cart');
        }

        $session = $stripeService->createCheckoutSession($cart);

        return new RedirectResponse($session->url);
    }

    // Route de validation de payement
    #[Route('/payment/success', name: 'payment_success')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    // Route d'annulation de payement
    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}