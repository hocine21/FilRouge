<?php

namespace App\Controller\api;

use App\Entity\Entrepot;
use App\Repository\EntrepotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/entrepots')]
class EntrepotController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private EntrepotRepository $entrepotRepository;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, EntrepotRepository $entrepotRepository)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->entrepotRepository = $entrepotRepository;
    }

    #[Route('/', name: 'api_entrepots_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $entrepots = $this->entrepotRepository->findAll();

        $data = [];
        foreach ($entrepots as $entrepot) {
            $data[] = [
                'id' => $entrepot->getId(),
                'nom' => $entrepot->getNom(),
                'ville' => $entrepot->getVille(),
                'codePostale' => $entrepot->getCodePostale(),
                'rue' => $entrepot->getRue(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_entrepots_show', methods: ['GET'])]
    public function show($id, EntrepotRepository $entrepotRepository): JsonResponse 
    {
        $entrepot = $entrepotRepository->find($id);

        if (!$entrepot) {
            return new JsonResponse(['message' => 'Entrepot not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $entrepot->getId(),
            'nom' => $entrepot->getNom(),
            'ville' => $entrepot->getVille(),
            'codePostale' => $entrepot->getCodePostale(),
            'rue' => $entrepot->getRue(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/create', name: 'api_entrepots_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entrepot = new Entrepot();
        $entrepot->setNom($data['nom']);
        $entrepot->setVille($data['ville']);
        $entrepot->setCodePostale($data['codePostale']);
        $entrepot->setRue($data['rue']);

        $errors = $this->validator->validate($entrepot);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($entrepot);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Entrepot créé avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_entrepots_update', methods: ['PUT'])]
    public function update($id, Request $request, EntrepotRepository $entrepotRepository): JsonResponse
    {
        $entrepot = $entrepotRepository->find($id);

        if (!$entrepot) {
            return new JsonResponse(['message' => 'Entrepot not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $entrepot->setNom($data['nom'] ?? $entrepot->getNom());
        $entrepot->setVille($data['ville'] ?? $entrepot->getVille());
        $entrepot->setCodePostale($data['codePostale'] ?? $entrepot->getCodePostale());
        $entrepot->setRue($data['rue'] ?? $entrepot->getRue());

        $errors = $this->validator->validate($entrepot);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Entrepot mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_entrepots_delete', methods: ['DELETE'])]
    public function delete($id, EntrepotRepository $entrepotRepository): JsonResponse
    {
        $entrepot = $entrepotRepository->find($id);

        if (!$entrepot) {
            return new JsonResponse(['message' => 'Entrepot not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($entrepot);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Entrepot supprimé avec succès'], Response::HTTP_OK);
    }
}
