<?php

namespace App\Controller\api;

use App\Entity\Entrepot;
use App\Repository\EntrepotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/entrepots')]
class EntrepotController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $validator;
    private $entrepotRepository;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, EntrepotRepository $entrepotRepository)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entrepotRepository = $entrepotRepository;
    }

    #[Route('/', name: 'api_entrepots_index', methods: ['GET'])]
    public function index(): Response
    {
        $entrepots = $this->entrepotRepository->findAll();
        $data = $this->serializer->serialize($entrepots, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/codes-postaux', name: 'api_entrepots_codes_postaux', methods: ['GET'])]
    public function getCodesPostaux(): Response
    {
        $codesPostaux = $this->entrepotRepository->findAllDistinctCodesPostaux();
        return new JsonResponse($codesPostaux, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_entrepots_show', methods: ['GET'])]
    public function show($id, EntrepotRepository $entrepotRepository): Response 
    {
        $entrepot = $entrepotRepository->find($id);

        if (!$entrepot) {
            return new JsonResponse(['message' => 'Entrepot not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($entrepot, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/create', name: 'api_entrepots_create', methods: ['POST'])]
    public function create(Request $request): Response
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
    public function update($id, Request $request, EntrepotRepository $entrepotRepository): Response
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
    public function delete($id, EntrepotRepository $entrepotRepository): Response
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
