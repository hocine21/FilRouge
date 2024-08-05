<?php
// src/Controller/api/ConnexionController.php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\Client;
use App\Entity\Employe;

class ConnexionController extends AbstractController
{
    private $tokenStorage;
    private $authorizationChecker;
    private $jwtManager;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/api/connexion', name: 'connexion', methods: ['POST'])]
    public function connexion(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $requiredFields = ['AdresseEmail', 'MotDePasse'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return $this->json(['error' => 'Tous les champs doivent être renseignés.'], 400);
                }
            }

            $user = $entityManager->getRepository(Client::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
            if ($user === null) {
                $user = $entityManager->getRepository(Employe::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
            }

            if ($user === null || !$passwordHasher->isPasswordValid($user, $data['MotDePasse'])) {
                throw new AuthenticationException('Adresse e-mail ou mot de passe incorrect.');
            }

            $authToken = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->tokenStorage->setToken($authToken);

            $token = $this->jwtManager->create($user);

            $response = [
                'token' => $token,
                'id' => $user->getId(),
                'roles' => $user->getRoles(),
                'success' => true,
                'redirect' => $this->generateUrl('app_index') // Valeur par défaut
            ];

            // Détermine la redirection en fonction du rôle
            if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
                $response['redirect'] = $this->generateUrl('app_super_admin');
            } elseif ($this->authorizationChecker->isGranted('ROLE_APPROVISIONNEMENT')) {
                $response['redirect'] = $this->generateUrl('app_appro');
            } elseif ($this->authorizationChecker->isGranted('ROLE_COMMERCIAL')) {
                $response['redirect'] = $this->generateUrl('app_commercial');
            }

            return new JsonResponse($response);

        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 401);
        }
    }
}
