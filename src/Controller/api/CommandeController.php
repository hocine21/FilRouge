<?php

namespace App\Controller\api;
use App\Entity\Detail;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\DetailRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/api/commande', name: 'api_create_commande', methods: ['POST'])]
    public function createCommande(
        Request $request,
        EntityManagerInterface $em,
        ClientRepository $clientRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['client_id']) || empty($data['adresse_facturation']) || empty($data['ville_facturation']) || empty($data['code_postal_facturation'])) {
            return new JsonResponse(['message' => 'Données manquantes'], Response::HTTP_BAD_REQUEST);
        }

        $client = $clientRepository->find($data['client_id']);
        if (!$client) {
            return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $commande = new Commande();
        $commande->setDateCommande(new \DateTime());
        $commande->setCodeQrCommande(null);
        $commande->setClient($client);
        $commande->setEtat('payé');
        $commande->setDemandeDevis(false);
        $commande->setEtatDevis(null);
        $commande->setRistourne(null);

        // Assurez-vous que ces champs existent et sont définis dans l'entité
        $commande->setAdresseFacturation($data['adresse_facturation']);
        $commande->setVilleFacturation($data['ville_facturation']);
        $commande->setCodePostalFacturation($data['code_postal_facturation']);

        $em->persist($commande);
        $em->flush();

        return new JsonResponse(['message' => 'Commande créée avec succès', 'id' => $commande->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/commandes', name: 'api_get_all_commandes', methods: ['GET'])]
    public function getAllCommandes(CommandeRepository $commandeRepository): JsonResponse
    {
        $commandes = $commandeRepository->findAll();

        $data = [];
        foreach ($commandes as $commande) {
            $data[] = [
                'id' => $commande->getId(),
                'client' => $commande->getClient() ? $commande->getClient()->getNom() : null,
                'date_commande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'code_qr_commande' => $commande->getCodeQrCommande(),
                'etat' => $commande->getEtat(),
                'demande_devis' => $commande->isDemandeDevis(),
                'etat_devis' => $commande->getEtatDevis(),
                'ristourne' => $commande->getRistourne(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/filtreCommande', name: 'api_filtre_commande', methods: ['GET'])]
    public function filtreCommande(CommandeRepository $commandeRepository, Request $request): JsonResponse
    {
        $etats = $request->query->get('etats');
        if (!$etats) {
            return new JsonResponse(['message' => 'Aucun état spécifié'], Response::HTTP_BAD_REQUEST);
        }

        $etatsArray = explode(',', $etats);
        $commandes = $commandeRepository->findByEtats($etatsArray);

        if (empty($commandes)) {
            return new JsonResponse(['message' => 'Aucune commande trouvée pour les états spécifiés'], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($commandes as $commande) {
            $data[] = [
                'id' => $commande->getId(),
                'client' => $commande->getClient() ? $commande->getClient()->getNom() : null,
                'date_commande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'code_qr_commande' => $commande->getCodeQrCommande(),
                'etat' => $commande->getEtat(),
                'demande_devis' => $commande->isDemandeDevis(),
                'etat_devis' => $commande->getEtatDevis(),
                'ristourne' => $commande->getRistourne(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/commandes/demande-devis', name: 'api_get_commandes_demande_devis', methods: ['GET'])]
    public function show(CommandeRepository $commandeRepository): JsonResponse
    {
        $commandes = $commandeRepository->findAll();
        $filteredCommandes = array_filter($commandes, fn($commande) => $commande->isDemandeDevis() === true);

        $data = [];
        foreach ($filteredCommandes as $commande) {
            $data[] = [
                'id' => $commande->getId(),
                'client' => $commande->getClient() ? $commande->getClient()->getNom() : null,
                'date_commande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'code_qr_commande' => $commande->getCodeQrCommande(),
                'etat' => $commande->getEtat(),
                'demande_devis' => $commande->isDemandeDevis(),
                'etat_devis' => $commande->getEtatDevis(),
                'ristourne' => $commande->getRistourne(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/commande/{id}/details', name: 'api_get_commande_details', methods: ['GET'])]
    public function getCommandeDetails(int $id, CommandeRepository $commandeRepository, DetailRepository $detailRepository): JsonResponse
    {
        $commande = $commandeRepository->find($id);
        if (!$commande) {
            return new JsonResponse(['message' => 'Commande non trouvée'], Response::HTTP_NOT_FOUND);
        }
    
        $details = $detailRepository->findBy(['commande' => $commande]);
    
        $data = [];
        foreach ($details as $detail) {
            $data[] = [
                'produit' => $detail->getProduit() ? $detail->getProduit()->getNomProduit() : null,
                'quantite' => $detail->getQuantite(),
                'prix_unitaire' => $detail->getPrixUnitaire(),
                'total' => $detail->getMontantTotal(),
            ];
        }
    
        return new JsonResponse([
            'commande' => [
                'id' => $commande->getId(),
                'client' => $commande->getClient() ? $commande->getClient()->getNom() : null,
                'date_commande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'code_qr_commande' => $commande->getCodeQrCommande(),
                'etat' => $commande->getEtat(),
                'demande_devis' => $commande->isDemandeDevis(),
                'etat_devis' => $commande->getEtatDevis(),
                'ristourne' => $commande->getRistourne(),
            ],
            'details' => $data,
        ], Response::HTTP_OK);
    }
}
