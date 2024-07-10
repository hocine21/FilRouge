<?php
// src/Controller/api/CheckRoleController.php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class CheckRoleController extends AbstractController
{
    #[Route('/api/check-role', name: 'check_role', methods: ['GET'])]
    public function checkRole(Request $request): JsonResponse
    {
        // Récupérer le token JWT de l'en-tête Authorization
        $authorizationHeader = $request->headers->get('Authorization');

        if (!$authorizationHeader) {
            throw new AccessDeniedException('Token d\'accès manquant dans l\'en-tête Authorization.');
        }

        // Extraire le token JWT de l'en-tête Authorization (format: Bearer <token>)
        $token = str_replace('Bearer ', '', $authorizationHeader);

        // Vérifier et décoder le token JWT
        try {
            $jwtSecret = 'your_jwt_secret'; // Clé secrète pour vérifier le JWT, doit correspondre à celle utilisée pour l'encodage

            $token = (new Parser())->parse((string) $token);

            // Valider le token JWT
            $data = new ValidationData();
            $data->setCurrentTime(time()); // Utiliser l'heure actuelle pour la validation (optionnel)

            if (!$token->validate($data)) {
                throw new AccessDeniedException('Token d\'accès invalide ou expiré.');
            }

            // Récupérer les informations du payload du token
            $userId = $token->getClaim('user_id');
            $roles = $token->getClaim('roles');

            // Vous pouvez également récupérer d'autres informations nécessaires à partir du token

            // Déterminer le rôle principal de l'utilisateur (ici, on suppose que le premier rôle est le principal)
            $mainRole = $roles[0];

            // Retourner les informations du rôle principal sous forme de réponse JSON
            return $this->json([
                'user_id' => $userId,
                'role' => $mainRole,
                // Ajoutez d'autres informations si nécessaire
            ]);
        } catch (\Exception $e) {
            throw new AccessDeniedException('Token d\'accès invalide ou expiré.');
        }
    }
}
