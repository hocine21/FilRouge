<?php

namespace App\Controller\api;

use App\Entity\Fournisseur;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/fournisseurs')]
class FournisseurApiController extends AbstractController

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

    #[Route('/', name: 'api_fournisseur_index', methods: ['GET'])]
    public function index(FournisseurRepository $fournisseurRepository): JsonResponse
    {
        $fournisseurs = $fournisseurRepository->findAll();
        $data = $this->serializer->serialize($fournisseurs, 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/nouveau', name: 'api_fournisseur_new', methods: ['POST'])]
    public function nouveau(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $fournisseur = new Fournisseur();
        $fournisseur->setNomFournisseur($data['nomFournisseur'] ?? null);
        $fournisseur->setTypeFourniture($data['typeFourniture'] ?? null);
        $fournisseur->setPrixHTFournisseur($data['prixHTFournisseur'] ?? null);

        $errors = $this->validator->validate($fournisseur);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($fournisseur);
        $this->entityManager->flush();

        return new JsonResponse('Fournisseur créé avec succès', JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_fournisseur_afficher', methods: ['GET'])]
    public function afficher(Fournisseur $fournisseur): JsonResponse
    {
        $data = $this->serializer->serialize($fournisseur, 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/{id}/modifier', name: 'api_fournisseur_modifier', methods: ['PUT', 'PATCH'])]
    public function modifier(Request $request, Fournisseur $fournisseur): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $fournisseur->setNomFournisseur($data['nomFournisseur'] ?? $fournisseur->getNomFournisseur());
        $fournisseur->setTypeFourniture($data['typeFourniture'] ?? $fournisseur->getTypeFourniture());
        $fournisseur->setPrixHTFournisseur($data['prixHTFournisseur'] ?? $fournisseur->getPrixHTFournisseur());

        $errors = $this->validator->validate($fournisseur);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse('Fournisseur mis à jour avec succès', JsonResponse::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_fournisseur_supprimer', methods: ['DELETE'])]
    public function supprimer(Fournisseur $fournisseur): JsonResponse
    {
        $this->entityManager->remove($fournisseur);
        $this->entityManager->flush();

        return new JsonResponse('Fournisseur supprimé avec succès', JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('/recherche', name: 'api_fournisseur_recherche', methods: ['GET'])]
    public function recherche(Request $request, FournisseurRepository $fournisseurRepository): JsonResponse
    {
        $term = $request->query->get('term', '');
        $fournisseurs = $fournisseurRepository->findByNomFournisseur($term);
        $data = $this->serializer->serialize($fournisseurs, 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
