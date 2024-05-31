<?php

namespace App\Controller\api;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories')]
class CategorieController extends AbstractController
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

    #[Route('/', name: 'api_categories_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $data = $this->serializer->serialize($categories, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_categories_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        $data = $this->serializer->serialize($categorie, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'api_categories_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setNomCategorie($data['nomCategorie']);

        $errors = $this->validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($categorie);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Categorie créée avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_categories_update', methods: ['PUT'])]
    public function update(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        if (!$categorie) {
            return new JsonResponse(['message' => 'Categorie non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $categorie->setNomCategorie($data['nomCategorie']);

        $errors = $this->validator->validate($categorie);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Categorie mise à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_categories_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $categorie = $this->entityManager->getRepository(Categorie::class)->find($id);
        if (!$categorie) {
            return new JsonResponse(['message' => 'Categorie non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($categorie);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Categorie supprimée avec succès'], Response::HTTP_OK);
    }

    #[Route('/search', name: 'api_categories_search', methods: ['GET'])]
    public function search(Request $request, CategorieRepository $categorieRepository): Response
    {
        $criteria = [];

        // Récupérer les caractères de recherche depuis la requête
        $searchTerm = $request->query->get('nomCategorie');

        // Vérifier si le terme de recherche contient au moins 3 caractères
        if ($searchTerm && strlen($searchTerm) >= 3) {
            // Utiliser le repository pour rechercher les catégories dont le nom commence par les caractères spécifiés
            $categories = $categorieRepository->findByNomCategorieStartingWith($searchTerm);

            // Sérialiser les résultats en JSON
            $data = $this->serializer->serialize($categories, 'json');

            // Retourner une réponse JSON avec les résultats de la recherche
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } else {
            // Retourner une erreur si le terme de recherche ne contient pas assez de caractères
            return new JsonResponse(['message' => 'La recherche doit contenir au moins 3 caractères'], Response::HTTP_BAD_REQUEST);
        }
    }
}