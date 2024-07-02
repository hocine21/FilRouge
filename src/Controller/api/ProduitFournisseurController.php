<?php

namespace App\Controller\api;

use App\Entity\Produit;
use App\Entity\Fournisseur;
use App\Entity\ProduitFournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProduitFournisseurController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/produit-fournisseur", name="api_produit_fournisseur_add", methods={"POST"})
     */
    public function addProduitFournisseur(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fournisseurId = $data['fournisseurId'] ?? null;
        $produits = $data['produits'] ?? [];
        $dateCommande = new \DateTime($data['dateCommande'] ?? 'now');

        if (!$fournisseurId || empty($produits)) {
            return new JsonResponse(['error' => 'Fournisseur ou produits non spécifiés.'], Response::HTTP_BAD_REQUEST);
        }

        $fournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($fournisseurId);
        if (!$fournisseur) {
            return new JsonResponse(['error' => 'Fournisseur introuvable.'], Response::HTTP_NOT_FOUND);
        }

        foreach ($produits as $produitData) {
            $produitId = $produitData['id'] ?? null;
            $quantite = (int) ($produitData['quantiteCommande'] ?? null); // Conversion forcée en entier

            if (!isset($quantite) || !is_int($quantite)) {
                return new JsonResponse(['error' => 'La quantité doit être un entier.'], Response::HTTP_BAD_REQUEST);
            }

            $produit = $this->entityManager->getRepository(Produit::class)->find($produitId);
            if (!$produit) {
                return new JsonResponse(['error' => "Produit ID $produitId introuvable."], Response::HTTP_NOT_FOUND);
            }

            $produitFournisseur = new ProduitFournisseur();
            $produitFournisseur->setFournisseur($fournisseur);
            $produitFournisseur->setProduit($produit);
            $produitFournisseur->setDateCommande($dateCommande);
            $produitFournisseur->setQuantiteCommande($quantite);

            // Par défaut, l'état de la commande est 'fait'
            $produitFournisseur->setEtatCommande('fait');

            // Assurez-vous que dateLivraison et etatLivraison sont définis à null si non spécifiés
            $produitFournisseur->setDateLivraison(null);
            $produitFournisseur->setEtatLivraison(null);

            // Ajout de la validation si nécessaire
            $errors = $this->validator->validate($produitFournisseur);
            if (count($errors) > 0) {
                // Gérer les erreurs de validation
                return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($produitFournisseur);
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            // Gérer l'exception et renvoyer une réponse appropriée
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['success' => true, 'message' => 'Relation Produit-Fournisseur ajoutée avec succès'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/produit-fournisseur/{id}", name="api_produit_fournisseur_update", methods={"PUT"})
     */
    public function updateProduitFournisseur(Request $request, ProduitFournisseur $produitFournisseur): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifiez si l'entité ProduitFournisseur existe
        if (!$produitFournisseur) {
            return new JsonResponse(['error' => 'ProduitFournisseur introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérez les données d'état de livraison et de date de livraison du JSON
        $etatLivraison = $data['etatLivraison'] ?? null;
        $dateLivraison = isset($data['dateLivraison']) ? new \DateTime($data['dateLivraison']) : null;

        // Mettez à jour l'entité ProduitFournisseur
        $produitFournisseur->setEtatLivraison($etatLivraison);
        $produitFournisseur->setDateLivraison($dateLivraison);

        // Validation de l'entité mise à jour
        $errors = $this->validator->validate($produitFournisseur);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['success' => true, 'message' => 'ProduitFournisseur mis à jour avec succès.'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/produit-fournisseur/{id}", name="api_produit_fournisseur_get", methods={"GET"})
     */
    public function getProduitFournisseur(ProduitFournisseur $produitFournisseur): JsonResponse
    {
        // Vérifiez si l'entité ProduitFournisseur existe
        if (!$produitFournisseur) {
            return new JsonResponse(['error' => 'ProduitFournisseur introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Renvoyez l'entité ProduitFournisseur sous forme de JSON
        $data = [
            'id' => $produitFournisseur->getId(),
            'fournisseur' => [
                'id' => $produitFournisseur->getFournisseur()->getId(),
                'nom' => $produitFournisseur->getFournisseur()->getNomFournisseur() // Modification ici
            ],
            'produit' => [
                'id' => $produitFournisseur->getProduit()->getId(),
                'nom' => $produitFournisseur->getProduit()->getNomProduit(), // Modification ici
                'largeur' => $produitFournisseur->getProduit()->getLargeurProduit(), // Modification ici
                'masse' => $produitFournisseur->getProduit()->getMasseProduit(), // Modification ici
                'epaisseur' => $produitFournisseur->getProduit()->getEpaisseurProduit(), // Modification ici
                'forme' => $produitFournisseur->getProduit()->getFormeProduit(), // Modification ici
                'hauteur' => $produitFournisseur->getProduit()->getHauteurProduit(), // Modification ici
                'section' => $produitFournisseur->getProduit()->getSectionProduit(), // Modification ici
            ],
            'etatCommande' => $produitFournisseur->getEtatCommande(),
            'etatLivraison' => $produitFournisseur->getEtatLivraison(),
            'dateCommande' => $produitFournisseur->getDateCommande()->format('Y-m-d H:i:s'),
            'dateLivraison' => $produitFournisseur->getDateLivraison() ? $produitFournisseur->getDateLivraison()->format('Y-m-d H:i:s') : null,
            'quantiteCommande' => $produitFournisseur->getQuantiteCommande(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/produit-fournisseur", name="api_produit_fournisseur_get_all", methods={"GET"})
     */
    public function getAllProduitFournisseur(): JsonResponse
    {
        $produitFournisseurs = $this->entityManager->getRepository(ProduitFournisseur::class)->findAll();

        $data = [];
        foreach ($produitFournisseurs as $produitFournisseur) {
            $data[] = [
                'id' => $produitFournisseur->getId(),
                'fournisseur' => [
                    'id' => $produitFournisseur->getFournisseur()->getId(),
                    'nom' => $produitFournisseur->getFournisseur()->getNomFournisseur() // Modification ici
                ],
                'produit' => [
                    'id' => $produitFournisseur->getProduit()->getId(),
                    'nom' => $produitFournisseur->getProduit()->getNomProduit(), // Modification ici
                    'largeur' => $produitFournisseur->getProduit()->getLargeurProduit(), // Modification ici
                    'masse' => $produitFournisseur->getProduit()->getMasseProduit(), // Modification ici
                    'epaisseur' => $produitFournisseur->getProduit()->getEpaisseurProduit(), // Modification ici
                    'forme' => $produitFournisseur->getProduit()->getFormeProduit(), // Modification ici
                    'hauteur' => $produitFournisseur->getProduit()->getHauteurProduit(), // Modification ici
                    'section' => $produitFournisseur->getProduit()->getSectionProduit(), // Modification ici
                ],
                'etatCommande' => $produitFournisseur->getEtatCommande(),
                'etatLivraison' => $produitFournisseur->getEtatLivraison(),
                'dateCommande' => $produitFournisseur->getDateCommande()->format('Y-m-d H:i:s'),
                'dateLivraison' => $produitFournisseur->getDateLivraison() ? $produitFournisseur->getDateLivraison()->format('Y-m-d H:i:s') : null,
                'quantiteCommande' => $produitFournisseur->getQuantiteCommande(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
