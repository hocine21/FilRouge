<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierCalculController extends AbstractController
{
    /**
     * @Route("/panier/calculer", name="panier_calculer", methods={"POST"})
     */
    public function calculerPanier(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier si les données du panier sont présentes
        if (!isset($data['panier']) || !is_array($data['panier'])) {
            return new JsonResponse(['message' => 'Données du panier manquantes ou invalides'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si l'utilisateur a le rôle ROLE_PROFESSIONNEL
        $isProfessionnel = $this->isGranted('ROLE_PROFESSIONNEL');

        // Initialiser le prix total du panier
        $totalTTC = 0;
        $totalHT = 0;
        $details = [];

        // Calculer le prix total du panier et récupérer les détails des produits
        foreach ($data['panier'] as $item) {
            // Adapter aux propriétés du panier
            $prixUnitaire = $item['variante']['prixML']; // Utiliser le prixML de la variante
            $longueurCentimetres = $item['longueur']; // Longueur en centimètres
            $longueurMetres = $longueurCentimetres / 100; // Convertir en mètres
            $quantite = $item['quantite'];
            $masseProduit = $item['variante']['masseProduit']; // Masse produit de la variante
            $estChute = $item['estChute'] ?? false; // Vérifier si c'est une chute (ajouter cette propriété dans votre panier)

            // Calculer le prix TTC
            $prixTTC = ($masseProduit * 0.3) * ($longueurMetres * $prixUnitaire) * $quantite;

            // Si c'est une chute, appliquer une réduction spécifique
            if ($estChute) {
                $prixTTC *= 0.5; // Appliquer une réduction de 50% pour les chutes (ajuster selon besoin)
            }

            // Calculer le prix HT
            $prixHT = $prixTTC / 1.20; // Supposons une TVA de 20%

            // Appliquer une réduction de 10 % si l'utilisateur est un professionnel
            if ($isProfessionnel) {
                $prixTTC *= 0.9; // Appliquer une réduction de 10 %
                $prixHT = $prixTTC / 1.20; // Recalculer le prix HT après réduction
            }

            $totalTTC += $prixTTC;
            $totalHT += $prixHT;

            // Préparer les détails du produit
            $produitDetails = [
                'nom' => $item['variante']['nomProduit'], // Nom du produit
                'longueur_cm' => $longueurCentimetres, // Longueur en centimètres
                'longueur_m' => $longueurMetres, // Longueur en mètres
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'prix_ttc' => $prixTTC, // Toujours afficher le prix TTC
                'details_calcul' => [
                    'masse_produit' => $masseProduit,
                    'prix_ml' => $prixUnitaire,
                    'longueur_cm' => $longueurCentimetres,
                    'longueur_m' => $longueurMetres,
                    'quantite' => $quantite,
                    'est_chute' => $estChute
                ]
            ];

            // Ajouter le prix HT seulement pour les professionnels
            if ($isProfessionnel) {
                $produitDetails['prix_ht'] = $prixHT; // Ajouter le prix HT
            }

            $details[] = $produitDetails;
        }

        // Préparer la réponse
        $response = [
            'panier' => $details,
            'total_ttc' => $totalTTC,
        ];

        // Ajouter le total HT seulement pour les professionnels
        if ($isProfessionnel) {
            $response['total_ht'] = $totalHT;
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
