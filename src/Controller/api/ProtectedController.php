<?php
namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProtectedController extends AbstractController
{
    #[Route('/api/protected', name: 'api_protected', methods: ['GET'])]
    public function protectedRoute(): JsonResponse
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être authentifié pour accéder à cette ressource.');
        }

        // Réponse si l'utilisateur est authentifié
        return $this->json(['message' => 'Vous avez accédé à une route protégée!']);
    }
}
