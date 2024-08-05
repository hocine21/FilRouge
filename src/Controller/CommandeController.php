<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Detail;
use App\Entity\Livraison;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommandeController extends AbstractController
{
    private $entityManager;
    private $produitRepository;
    private $stockRepository;
    private $validator;
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProduitRepository $produitRepository,
        StockRepository $stockRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->produitRepository = $produitRepository;
        $this->stockRepository = $stockRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    #[Route('/process-order', name: 'process_order', methods: ['POST'])]
    public function processOrder(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Valider les données
        $errors = $this->validator->validate($data);
        if (count($errors) > 0) {
            return $this->json([
                'status' => 'error',
                'message' => (string) $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        // Créer la commande
        $commande = new Commande();
        $commande->setDateCommande(new \DateTime());
        $commande->setEtat('Fait'); // Mettre l'état approprié
        $commande->setDemandeDevis(false);
        $commande->setEtatDevis('');
        $commande->setRistourne(null); // Si applicable
        $commande->setClient($this->getUser()); // Remplacer par le client authentifié

        $this->entityManager->persist($commande);

        // Créer les détails de la commande
        foreach ($data['details'] as $detailData) {
            $produit = $this->produitRepository->find($detailData['produit_id']);
            if (!$produit) {
                continue; // ou gérer l'erreur comme vous le souhaitez
            }

            // Vérifier la disponibilité en stock
            $stocks = $this->stockRepository->findBy(['produit' => $produit]);
            $totalLongueurDisponible = array_sum(array_map(fn($stock) => $stock->getLongueur() * $stock->getQuantite(), $stocks));

            if ($totalLongueurDisponible < $detailData['longueur']) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Stock insuffisant pour le produit ' . $produit->getId(),
                ], Response::HTTP_BAD_REQUEST);
            }

            // Calculer la quantité de barres nécessaires
            $longueurRestante = $detailData['longueur'];
            foreach ($stocks as $stock) {
                while ($longueurRestante > 0 && $stock->getQuantite() > 0) {
                    if ($stock->getLongueur() <= $longueurRestante) {
                        $longueurRestante -= $stock->getLongueur();
                        $stock->setQuantite($stock->getQuantite() - 1);
                    } else {
                        $stock->setLongueur($stock->getLongueur() - $longueurRestante);
                        $longueurRestante = 0;
                    }
                    $this->entityManager->persist($stock);
                }
                if ($longueurRestante <= 0) break;
            }

            // Créer et ajouter les détails de la commande
            $detail = new Detail();
            $detail->setQuantite($detailData['quantite']);
            $detail->setLongueur($detailData['longueur']);
            $detail->setPrixUnitaire($detailData['prix_unitaire']);
            $detail->setMontantTotal($detailData['montant_total']);
            $detail->setProduit($produit);
            $detail->setCommande($commande);

            $this->entityManager->persist($detail);
        }

        // Créer la livraison
        $livraison = new Livraison();
        $livraison->setDateLivraison(new \DateTime());
        $livraison->setStatutLivraison('En attente'); // Mettre l'état approprié
        $livraison->setAdresseLivraison($data['shipping_address']);
        $livraison->setQrCodeLivraison(''); // Générer un QR code si nécessaire

        $this->entityManager->persist($livraison);

        // Lier la livraison à la commande
        $commandeLivraison = new CommandeLivraison();
        $commandeLivraison->setCommande($commande);
        $commandeLivraison->setLivraison($livraison);

        $this->entityManager->persist($commandeLivraison);

        // Sauvegarder les changements
        $this->entityManager->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Commande enregistrée avec succès!',
        ]);
    }
}
