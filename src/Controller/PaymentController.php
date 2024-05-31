<?php
// src/Controller/PaymentController.php
// src/Controller/PaymentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends AbstractController
{
    #[Route('/checkout', name: 'checkout', methods: ['GET'])]
    public function checkout(): Response
    {
        return $this->render('payment/checkout.html.twig', [
            'nom_produit' => 'Mon Produit', // Nom du produit
            'prix' => 20 // Prix du produit
        ]);
    }
    #[Route('/create-checkout-session', name: 'create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(Request $request): Response
    {
        Stripe::setApiKey('sk_test_51OlTHRA0KVqNVnNN17z9SGuimwnY8MbCnAAKwutlIrxxCk1kiWrEHRbiVD7rCzkS6cScAzDHXAMoSAJSjtjiMjEO00mfG7DPI9');
    
        try {
            $prix = $request->request->get('prix') * 100;
    
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Produit',
                        ],
                        'unit_amount' => $prix,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', [], 0),
                'cancel_url' => $this->generateUrl('payment_cancel', [], 0),
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                ],
                'billing_address_collection' => 'required',
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic',
                    ],
                ],
                // Demander à Stripe de collecter les informations de carte
                'payment_method_types' => [
                    'card',
                ],
            ]);
    
            return $this->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
