<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EntrepotRepository;
use Symfony\Component\HttpClient\HttpClient;

class DistanceController extends AbstractController
{
    private $entrepotRepository;

    public function __construct(EntrepotRepository $entrepotRepository)
    {
        $this->entrepotRepository = $entrepotRepository;
    }

    /**
     * @Route("/distance/{postalCode1}/{masseKgPerMeter}/{length}/{quantity}", name="calculate_distance")
     */
    public function calculateDistance($postalCode1, $masseKgPerMeter, $length, $quantity): Response
    {
        // Construction de la réponse avec les données de l'URL
        $responseDetails = 'Données de l\'URL : <br>';
        $responseDetails .= 'Code postal 1 : ' . $postalCode1 . '<br>';
        $responseDetails .= 'Masse par kilogramme par mètre : ' . $masseKgPerMeter . '<br>';
        $responseDetails .= 'Longueur : ' . $length . '<br>';
        $responseDetails .= 'Quantité : ' . $quantity . '<br>';

        // Récupérer les coordonnées géographiques du code postal 1
        $coordinates1 = $this->getCoordinates($postalCode1);

        if (!$coordinates1) {
            throw $this->createNotFoundException('Le code postal ' . $postalCode1 . ' n\'existe pas.');
        }

        // Récupérer tous les codes postaux de la table entrepot
        $codesPostaux = $this->entrepotRepository->findAllDistinctCodesPostaux();

        // Initialiser la distance la plus courte avec une valeur grande
        $shortestDistance = PHP_FLOAT_MAX;
        $nearestPostalCode = ''; 

        // Calculer la distance entre le code postal 1 et chaque code postal disponible
        foreach ($codesPostaux as $codePostal) {
            $coordinates2 = $this->getCoordinates($codePostal['codePostale']);

            if ($coordinates2) {
                $distance = $this->calculateDistanceBetweenPoints($coordinates1, $coordinates2);
                $responseDetails .= 'Distance entre ' . $postalCode1 . ' et ' . $codePostal['codePostale'] . ' : ' . $distance . ' km <br>';

                // Mettre à jour la distance la plus courte si une distance plus courte est trouvée
                if ($distance < $shortestDistance) {
                    $shortestDistance = $distance;
                    $nearestPostalCode = $codePostal['codePostale'];
                }
            }
        }

        // Calculer les frais de livraison
        $deliveryCost = $this->calculateDeliveryCost($shortestDistance, $masseKgPerMeter, $length, $quantity);

        // Ajouter les frais de livraison à la réponse
        $responseDetails .= 'Coût de livraison : ' . $deliveryCost . ' € <br>';

        $responseDetails .= 'La distance la plus courte est ' . $shortestDistance . ' km avec le code postal ' . $nearestPostalCode . '<br>';

        return new Response($responseDetails);
    }

    // Méthode pour récupérer les coordonnées géographiques d'un code postal
    private function getCoordinates($postalCode)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search/?q=' . $postalCode);
        $data = $response->toArray();

        if (!empty($data['features'][0]['geometry']['coordinates'])) {
            $coordinates = $data['features'][0]['geometry']['coordinates'];
            return ['latitude' => $coordinates[1], 'longitude' => $coordinates[0]];
        } else {
            return null;
        }
    }

    // Méthode pour calculer la distance entre deux points géographiques
    private function calculateDistanceBetweenPoints($point1, $point2)
    {
        $earthRadius = 6371;

        $lat1 = deg2rad($point1['latitude']);
        $lon1 = deg2rad($point1['longitude']);
        $lat2 = deg2rad($point2['latitude']);
        $lon2 = deg2rad($point2['longitude']);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    // Méthode pour calculer les frais de livraison
    private function calculateDeliveryCost($distance, $masseKgPerMeter, $length, $quantity)
    {
        $baseCost = 40; // Coût de base HT en euros
        $costPerKm = 0.3; // Coût par kilomètre en euros
        $additionalCostPer200Kg = 20; // Coût supplémentaire par tranche de 200 kg en euros

        // Calculer le poids total
        $totalWeight = $masseKgPerMeter * $length * $quantity;

        // Calculer le coût de base
        $transportCost = $baseCost + ($distance * $costPerKm);

        // Calculer les frais supplémentaires en fonction du poids
        if ($totalWeight > 200) {
            $additionalWeightCost = ceil(($totalWeight - 200) / 200) * $additionalCostPer200Kg;
            $transportCost += $additionalWeightCost;
        }

        // Ajouter la TVA
        $tva = $transportCost * 0.20; // Calculer la TVA
        $totalCostTTC = $transportCost + $tva; // Coût total TTC

        return round($totalCostTTC, 2);
    }
}
