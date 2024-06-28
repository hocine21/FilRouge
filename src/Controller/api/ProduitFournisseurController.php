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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/produit-fournisseur", name="api_produit_fournisseur_add", methods={"POST"})
     */
    public function addProduitFournisseur(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fournisseurId = $data['fournisseurId'] ?? null;
        $produits = $data['produits'] ?? [];
        $date = new \DateTime($data['date'] ?? 'now');

        if (!$fournisseurId || empty($produits)) {
            return new JsonResponse(['error' => 'Fournisseur ou produits non spécifiés.'], Response::HTTP_BAD_REQUEST);
        }

        $fournisseur = $this->entityManager->getRepository(Fournisseur::class)->find($fournisseurId);
        if (!$fournisseur) {
            return new JsonResponse(['error' => 'Fournisseur introuvable.'], Response::HTTP_NOT_FOUND);
        }

        foreach ($produits as $produitData) {
            $produitId = $produitData['id'] ?? null;
            $quantite = $produitData['quantity'] ?? null;

            $produit = $this->entityManager->getRepository(Produit::class)->find($produitId);
            if (!$produit) {
                return new JsonResponse(['error' => "Produit ID $produitId introuvable."], Response::HTTP_NOT_FOUND);
            }

            $produitFournisseur = new ProduitFournisseur();
            $produitFournisseur->setFournisseur($fournisseur);
            $produitFournisseur->setProduit($produit);
            $produitFournisseur->setDate($date);
            $produitFournisseur->setQuantite($quantite);

            $errors = $validator->validate($produitFournisseur);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($produitFournisseur);
        }

        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Relation Produit-Fournisseur ajoutée avec succès'], Response::HTTP_CREATED);
    }
}
