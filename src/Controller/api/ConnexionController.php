<?php
// ConnexionController.php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use App\Entity\Client;
use App\Entity\Employe;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion', methods: ['POST'])]
    public function connexion(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Récupérer les données de la requête JSON
            $data = json_decode($request->getContent(), true);

            // Vérifier si les champs nécessaires sont présents
            $requiredFields = ['email', 'password'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return $this->json(['error' => 'Tous les champs doivent être renseignés.'], 400);
                }
            }

            // Vérifier si l'utilisateur est un client
            $user = $entityManager->getRepository(Client::class)->findOneBy(['adresse_email' => $data['email']]);
            if ($user === null) {
                // Vérifier si l'utilisateur est un employé
                $user = $entityManager->getRepository(Employe::class)->findOneBy(['adresse_email' => $data['email']]);
            }

            // Si aucun utilisateur n'est trouvé
            if ($user === null) {
                throw new AuthenticationException('Adresse e-mail ou mot de passe incorrect.');
            }

            // Vérifier si le mot de passe est valide
            if (!$passwordHasher->isPasswordValid($user, $data['password'])) {
                throw new AuthenticationException('Adresse e-mail ou mot de passe incorrect.');
            }

            // Générer un jeton JWT
            $tokenPayload = [
                'user_id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'roles' => $user->getRoles(),
                'exp' => time() + 3600, // Expire dans 1 heure
            ];

            $jwtSecret = 'your_jwt_secret'; // Clé secrète pour signer le JWT, à remplacer par une valeur sécurisée

            $token = JWT::encode($tokenPayload, $jwtSecret);

            // Redirection en fonction du rôle de l'utilisateur
            switch (true) {
                case in_array('ROLE_SUPER_ADMIN', $user->getRoles()):
                    return $this->redirectToRoute('app_super_admin');
                    break;
                // Ajouter d'autres cas pour d'autres rôles si nécessaire
                default:
                    throw new AuthenticationException('Vous n\'avez pas les autorisations nécessaires.');
            }

        } catch (\Throwable $e) {
            // Capturer toute exception générée pendant le processus d'authentification
            return $this->json(['error' => $e->getMessage()], 401);
        }
    }
}
