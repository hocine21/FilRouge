<?php

namespace App\Controller\api;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/commandes")
 */
class CommandeController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): JsonResponse
    {
        $commandes = $commandeRepository->findAll();
        $data = [];

        foreach ($commandes as $commande) {
            $codeQrCommande = $commande->getCodeQrCommande();
            $data[] = [
                'id' => $commande->getId(),
                'DateCommande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
                'CodeQrCommande' => $codeQrCommande ? base64_encode(stream_get_contents($codeQrCommande)) : null,
                'Etat' => $commande->getEtat(),
                'DemandeDevis' => $commande->isDemandeDevis(),
                'EtatDevis' => $commande->getEtatDevis(),
                'Ristourne' => $commande->getRistourne(),
                'Client' => $commande->getClient() ? $commande->getClient()->getId() : null
            ];
        }

        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(int $id, CommandeRepository $commandeRepository): JsonResponse
    {
        $commande = $commandeRepository->find($id);
        if (!$commande) {
            return new JsonResponse(['message' => 'Commande non trouvée'], 404);
        }

        $codeQrCommande = $commande->getCodeQrCommande();
        $data = [
            'id' => $commande->getId(),
            'DateCommande' => $commande->getDateCommande()->format('Y-m-d H:i:s'),
            'CodeQrCommande' => $codeQrCommande ? base64_encode(stream_get_contents($codeQrCommande)) : null,
            'Etat' => $commande->getEtat(),
            'DemandeDevis' => $commande->isDemandeDevis(),
            'EtatDevis' => $commande->getEtatDevis(),
            'Ristourne' => $commande->getRistourne(),
            'Client' => $commande->getClient() ? $commande->getClient()->getId() : null
        ];

        return new JsonResponse($data, 200);
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $commande = $this->serializer->deserialize($data, Commande::class, 'json');

        // Decode the base64 QR code if not null
        $codeQrCommande = $commande->getCodeQrCommande();
        if ($codeQrCommande !== null) {
            $decodedQrCode = base64_decode($codeQrCommande);
            $commande->setCodeQrCommande($decodedQrCode);
        }

        $errors = $this->validator->validate($commande);
        if (count($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, 400, [], true);
        }

        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Commande créée'], 201);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request, CommandeRepository $commandeRepository): JsonResponse
    {
        $commande = $commandeRepository->find($id);
        if (!$commande) {
            return new JsonResponse(['message' => 'Commande non trouvée'], 404);
        }

        $data = $request->getContent();
        $this->serializer->deserialize($data, Commande::class, 'json', ['object_to_populate' => $commande]);

        // Decode the base64 QR code if not null
        $codeQrCommande = $commande->getCodeQrCommande();
        if ($codeQrCommande !== null) {
            $decodedQrCode = base64_decode($codeQrCommande);
            $commande->setCodeQrCommande($decodedQrCode);
        }

        $errors = $this->validator->validate($commande);
        if (count($errors) > 0) {
            $errorsString = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorsString, 400, [], true);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Commande mise à jour'], 200);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete(int $id, CommandeRepository $commandeRepository): JsonResponse
    {
        $commande = $commandeRepository->find($id);
        if (!$commande) {
            return new JsonResponse(['message' => 'Commande non trouvée'], 404);
        }

        $this->entityManager->remove($commande);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Commande supprimée'], 200);
    }
}
