<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;

class ConfigurateurController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/produits/search/nom/{nom_produit}", name="api_produits_search_nom", methods={"GET"})
     */
    public function searchByNom($nom_produit): JsonResponse
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findBy(['NomProduit' => $nom_produit]);

        if (!$produits) {
            return new JsonResponse(['message' => 'Aucun produit trouvé pour ce nom'], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($produits as $produit) {
            if ($produit->getMasseProduit() !== null) {
                $data[] = [
                    'nom_produit' => $produit->getNomProduit(),
                    'masse_produit' => $produit->getMasseProduit(),
                    // Ajoutez d'autres propriétés de l'entité Produit que vous souhaitez inclure ici
                ];
            }
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/api/produits/search/nom/{nom_produit}/masse/{masse_produit}", name="api_produits_search_nom_masse", methods={"GET"}, requirements={"masse_produit"=".+"})
     */
    public function searchByNomAndMasse($nom_produit, $masse_produit): JsonResponse
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findBy(['NomProduit' => $nom_produit, 'MasseProduit' => $masse_produit]);

        if (!$produits) {
            return new JsonResponse(['message' => 'Aucun produit trouvé pour ce nom et cette masse'], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($produits as $produit) {
            $produitData = [
                'nom_produit' => $produit->getNomProduit(),
                'masse_produit' => $produit->getMasseProduit(),
                'largeur_produit' => $produit->getLargeurProduit(),
                'epaisseur_produit' => $produit->getEpaisseurProduit(),
                'forme_produit' => $produit->getFormeProduit(),
                'hauteur_produit' => $produit->getHauteurProduit(),
                'section_produit' => $produit->getSectionProduit(),
                // Ajoutez d'autres propriétés de l'entité Produit que vous souhaitez inclure ici
            ];

            // Exclure les propriétés avec des valeurs nulles
            $produitData = array_filter($produitData, function ($value) {
                return !is_null($value);
            });

            $data[] = $produitData;
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
