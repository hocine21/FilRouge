<?php

namespace App\Controller\api;

use App\Entity\Produit;
use App\Entity\Stock;
use App\Repository\StockRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    private $entityManager;
    private $stockRepository;
    private $produitRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        StockRepository $stockRepository,
        ProduitRepository $produitRepository
    ) {
        $this->entityManager = $entityManager;
        $this->stockRepository = $stockRepository;
        $this->produitRepository = $produitRepository;
    }

    #[Route('/api/mise-a-jour-stock', name: 'mise_a_jour_stock', methods: ['POST'])]
    public function updateStock(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si les données nécessaires sont présentes
        if (!isset($data['cart']) || !is_array($data['cart'])) {
            return $this->json([
                'status' => 'error',
                'message' => 'Données insuffisantes.',
            ], Response::HTTP_BAD_REQUEST);
        }

        foreach ($data['cart'] as $item) {
            if (!isset($item['produit_id']) || !isset($item['longueur']) || !isset($item['quantite'])) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Données insuffisantes pour un ou plusieurs articles.',
                ], Response::HTTP_BAD_REQUEST);
            }

            $produitId = $item['produit_id'];
            $longueurDemandee = $item['longueur'];
            $quantiteDemandee = $item['quantite'];

            // Trouvez le produit par son ID
            $produit = $this->produitRepository->find($produitId);
            if (!$produit) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Produit non trouvé.',
                ], Response::HTTP_NOT_FOUND);
            }

            // Trouvez tous les stocks pour le produit spécifié
            $stocks = $this->stockRepository->findBy(['Produit' => $produit]);

            // Trier les stocks par longueur décroissante pour utiliser les plus longs en premier
            usort($stocks, function ($a, $b) {
                return $b->getLongueur() <=> $a->getLongueur();
            });

            // Déterminez la longueur totale demandée
            $longueurTotaleDemandee = $longueurDemandee * $quantiteDemandee;

            foreach ($stocks as $stock) {
                if ($longueurTotaleDemandee <= 0) break;

                $stockLongueur = $stock->getLongueur();
                $stockQuantite = $stock->getQuantite();

                // Calculer la longueur totale disponible dans ce stock
                $longueurDisponible = $stockLongueur * $stockQuantite;

                if ($longueurTotaleDemandee <= $longueurDisponible) {
                    while ($longueurTotaleDemandee > 0 && $stockQuantite > 0) {
                        if ($longueurTotaleDemandee <= $stockLongueur) {
                            // La demande peut être satisfaite avec la barre actuelle
                            if ($longueurTotaleDemandee == $stockLongueur) {
                                // Exactement une barre est utilisée
                                $stockQuantite--;
                                if ($stockQuantite <= 0) {
                                    $this->entityManager->remove($stock);
                                } else {
                                    $stock->setQuantite($stockQuantite);
                                    $this->entityManager->persist($stock);
                                }
                                $longueurTotaleDemandee = 0;
                            } else {
                                // Réduire la longueur de la barre actuelle
                                $stock->setLongueur($stockLongueur - $longueurTotaleDemandee);
                                $this->entityManager->persist($stock);
                                $longueurTotaleDemandee = 0;
                            }
                        } else {
                            // Utiliser plusieurs barres pour satisfaire la demande
                            $longueurTotaleDemandee -= $stockLongueur;
                            $stockQuantite--;
                            if ($stockQuantite <= 0) {
                                $this->entityManager->remove($stock);
                            } else {
                                $this->entityManager->persist($stock);
                            }
                        }
                    }
                } else {
                    // Si ce stock ne peut pas satisfaire la demande entièrement
                    $longueurTotaleDemandee -= $longueurDisponible;
                    $this->entityManager->remove($stock);
                }
            }

            // Si la demande de longueur n'est pas entièrement couverte
            if ($longueurTotaleDemandee > 0) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Stock insuffisant pour couvrir la demande.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        $this->entityManager->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Stock mis à jour avec succès.',
        ]);
    }
}
