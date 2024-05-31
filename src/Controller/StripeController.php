<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;

class StripeController extends AbstractController
{
    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(Request $request): Response
    {
        // Récupérer le prix depuis le formulaire
        $prix = $request->request->get('prix');

        // Initialiser Stripe avec votre clé privée
        Stripe::setApiKey('VOTRE_CLE_SECRETE_STRIPE');

        // Effectuer le paiement avec Stripe

        // Rediriger ou afficher un message de confirmation
        return new Response("Paiement réussi pour un montant de $prix €.");
    }
}
