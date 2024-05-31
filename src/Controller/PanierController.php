<?php

namespace App\Controller;

use App\Entity\Detail;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PanierController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/panier/ajouter", name="panier_ajouter", methods={"POST"})
     */
    public function ajouterProduitAuPanier(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier si les données requises sont présentes
        if (!isset($data['produit_id'], $data['longueur'], $data['quantite'])) {
            return new JsonResponse(['message' => 'Données requises manquantes'], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer le produit depuis la base de données
        $produit = $this->entityManager->find(Produit::class, $data['produit_id']);

        if (!$produit) {
            return new JsonResponse(['message' => 'Produit non trouvé'], Response::HTTP_NOT_FOUND);
        }

        // Calculer le prix unitaire
        $prixUnitaire = $produit->getPrixML();

        // Convertir la longueur de cm en m
        $longueurMetres = $data['longueur'] / 100;

        // Calculer le prix TTC
        $prixTTC = ($produit->getMasseProduit() * 0.3) * ($longueurMetres * $prixUnitaire) * $data['quantite'];

        // Créer un nouveau détail avec les informations fournies
        $detail = new Detail();
        $detail->setProduit($produit);
        $detail->setLongueur($longueurMetres);
        $detail->setQuantite($data['quantite']);

        // Ajouter le détail au panier (ici, le panier est simulé avec une variable de session)
        $panier = $request->getSession()->get('panier', []);
        $panier[] = $detail;
        $request->getSession()->set('panier', $panier);

        return new JsonResponse([
            'message' => 'Produit ajouté au panier',
            'prix_unitaire' => $prixUnitaire,
            'prix_ttc' => $prixTTC,
            'details_calcul' => [
                'masse_produit' => $produit->getMasseProduit(),
                'prix_ml' => $prixUnitaire,
                'longueur_m' => $longueurMetres,
                'quantite' => $data['quantite']
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/panier", name="panier_afficher", methods={"GET"})
     */
    public function afficherPanier(Request $request): JsonResponse
    {
        // Récupérer le panier depuis la session
        $panier = $request->getSession()->get('panier', []);

        // Initialiser le prix total du panier
        $total = 0;

        // Créer un tableau pour stocker les détails du panier avec les informations détaillées
        $details = [];

        // Calculer le prix total du panier et récupérer les détails des produits
        foreach ($panier as $detail) {
            $produit = $detail->getProduit();
            $prixUnitaire = $produit->getPrixML();
            $longueurMetres = $detail->getLongueur();
            $quantite = $detail->getQuantite();
            $prixTTC = ($produit->getMasseProduit() * 0.3) * ($longueurMetres * $prixUnitaire) * $quantite;
            $total += $prixTTC;
            $details[] = [
                'nom' => $produit->getNomProduit(),
                'longueur_cm' => $detail->getLongueur() * 100, // Convertir la longueur de m en cm pour l'affichage
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'prix_ttc' => $prixTTC,
                'details_calcul' => [
                    'masse_produit' => $produit->getMasseProduit(),
                    'prix_ml' => $prixUnitaire,
                    'longueur_m' => $longueurMetres,
                    'quantite' => $quantite
                ]
            ];
        }

        return new JsonResponse(['panier' => $details, 'total' => $total], Response::HTTP_OK);
    }

    /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer", methods={"DELETE"})
     */
    public function supprimerDetailPanier(Request $request, Detail $detail): JsonResponse
    {
        // Supprimer le détail du panier
        $this->entityManager->remove($detail);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Détail du panier supprimé'], Response::HTTP_OK);
    }

    /**
     * @Route("/panier/vider", name="panier_vider", methods={"DELETE"})
     */
    public function viderPanier(Request $request): JsonResponse
    {
        // Vider le panier en supprimant la variable de session
        $request->getSession()->remove('panier');

        return new JsonResponse(['message' => 'Panier vidé'], Response::HTTP_OK);
    }
}
