<?php

namespace App\Controller\api;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/produits")
 */
class ProduitController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/api/produit/nouveau", name="api_produits_new", methods={"POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Vérification des données requises
        if (!isset($data['categorie_id'], $data['nom_produit'], $data['image'], $data['prix_ml'])) {
            return new JsonResponse(['message' => 'Missing required data'], Response::HTTP_BAD_REQUEST);
        }
    
        // Récupération de la catégorie
        $categorie = $categorieRepository->find($data['categorie_id']);
        if (!$categorie) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    
        // Création du produit
        $produit = new Produit();
        $produit->setCategorie($categorie);
        $produit->setNomProduit($data['nom_produit']);
        $produit->setImage($data['image']);
        $produit->setPrixML($data['prix_ml']);
    
        // Paramètres facultatifs
        if (isset($data['largeur_produit'])) {
            $produit->setLargeurProduit($data['largeur_produit']);
        }
        if (isset($data['marge'])) {
            $produit->setMarge($data['marge']);
        }
        if (isset($data['epaisseur_produit'])) {
            $produit->setEpaisseurProduit($data['epaisseur_produit']);
        }
        if (isset($data['hauteur_produit'])) {
            $produit->setHauteurProduit($data['hauteur_produit']);
        }
        if (isset($data['masse_produit'])) {
            $produit->setMasseProduit($data['masse_produit']);
        }
        if (isset($data['forme_produit'])) {
            $produit->setFormeProduit($data['forme_produit']);
        }
        if (isset($data['section_produit'])) {
            $produit->setSectionProduit($data['section_produit']);
        }
    
        $entityManager->persist($produit);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Produit created!', 'id' => $produit->getId()], Response::HTTP_CREATED);
    }
 /**
     * @Route("/", name="api_produits_list", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();
        $data = [];

        foreach ($produits as $produit) {
            $data[] = [
                'id' => $produit->getId(),
                'nom_produit' => $produit->getNomProduit(),
                'image' => $produit->getImage(),
                'prix_ml' => $produit->getPrixML(),
                'epaisseur_produit' => $produit->getEpaisseurProduit(),
                'hauteur_produit' => $produit->getHauteurProduit(),
                'largeur_produit' => $produit->getLargeurProduit(),
                'masse_produit' => $produit->getMasseProduit(),
                'marge' => $produit->getMarge(),
                'forme_produit' => $produit->getFormeProduit(),
                'section_produit' => $produit->getSectionProduit()
                // Ajoutez d'autres propriétés de l'entité Produit ici
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="api_produits_show", methods={"GET"})
     */
    public function show($id): JsonResponse
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return new JsonResponse(['message' => 'Produit not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $produit->getId(),
            'nom_produit' => $produit->getNomProduit(),
            'image' => $produit->getImage(),
            'prix_ml' => $produit->getPrixML(),
            'epaisseur_produit' => $produit->getEpaisseurProduit(),
            'hauteur_produit' => $produit->getHauteurProduit(),
            'largeur_produit' => $produit->getLargeurProduit(),
            'masse_produit' => $produit->getMasseProduit(),
            'marge' => $produit->getMarge(),
            'forme_produit' => $produit->getFormeProduit(),
            'section_produit' => $produit->getSectionProduit()
            // Ajoutez d'autres propriétés de l'entité Produit ici
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
 * @Route("/update/{id}", name="api_produits_update", methods={"PUT"})
 */
public function update(Request $request, $id): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    $produit = $this->entityManager->getRepository(Produit::class)->find($id);

    if (!$produit) {
        return new JsonResponse(['message' => 'Produit not found'], Response::HTTP_NOT_FOUND);
    }

    // Mettre à jour les propriétés du produit
    // Assurez-vous de valider et de filtrer les données d'entrée si nécessaire

    if (isset($data['nom_produit'])) {
        $produit->setNomProduit($data['nom_produit']);
    }

    if (isset($data['image'])) {
        $produit->setImage($data['image']);
    }

    if (isset($data['prix_ml'])) {
        $produit->setPrixML($data['prix_ml']);
    }

    if (isset($data['epaisseur_produit'])) {
        $produit->setEpaisseurProduit($data['epaisseur_produit']);
    }

    if (isset($data['hauteur_produit'])) {
        $produit->setHauteurProduit($data['hauteur_produit']);
    }

    if (isset($data['largeur_produit'])) {
        $produit->setLargeurProduit($data['largeur_produit']);
    }

    if (isset($data['masse_produit'])) {
        $produit->setMasseProduit($data['masse_produit']);
    }

    if (isset($data['marge'])) {
        $produit->setMarge($data['marge']);
    }

    if (isset($data['forme_produit'])) {
        $produit->setFormeProduit($data['forme_produit']);
    }

    if (isset($data['section_produit'])) {
        $produit->setSectionProduit($data['section_produit']);
    }

    $this->entityManager->flush();

    return new JsonResponse(['message' => 'Produit updated!'], Response::HTTP_OK);
}


    /**
     * @Route("/delete/{id}", name="api_produits_delete", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            return new JsonResponse(['message' => 'Produit not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($produit);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Produit deleted!'], Response::HTTP_OK);
    }
}
