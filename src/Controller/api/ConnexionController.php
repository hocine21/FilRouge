<?php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Form\ConnexionType;
use App\Entity\Client;
use App\Entity\Employe;

class ConnexionController extends AbstractController
{
    private $tokenStorage;
    private $authorizationChecker;
    private $jwtManager;
    private $csrfTokenManager;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        JWTTokenManagerInterface $jwtManager,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->jwtManager = $jwtManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    #[Route('/api/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(ConnexionType::class);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                // Vérifiez les champs requis
                $requiredFields = ['AdresseEmail', 'MotDePasse', '_csrf_token'];
                foreach ($requiredFields as $field) {
                    if (!isset($data[$field]) || empty($data[$field])) {
                        return $this->json(['error' => 'Tous les champs doivent être renseignés.'], 400);
                    }
                }
    
                // Validation du token CSRF
                $csrfToken = new CsrfToken('authenticate', $data['_csrf_token']);
                if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
                    return $this->json(['error' => 'Invalid CSRF token.'], 400);
                }
    
                // Recherche de l'utilisateur
                $user = $entityManager->getRepository(Client::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
                if ($user === null) {
                    $user = $entityManager->getRepository(Employe::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
                }
    
                // Vérification du mot de passe
                if ($user === null || !$passwordHasher->isPasswordValid($user, $data['MotDePasse'])) {
                    throw new AuthenticationException('Adresse e-mail ou mot de passe incorrect.');
                }
    
                // Authentification et génération du JWT
                $authToken = new UsernamePasswordToken($user, 'main', $user->getRoles());
                $this->tokenStorage->setToken($authToken);
                $token = $this->jwtManager->create($user);
    
                // Préparez la réponse
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
    
        // Rendre le formulaire à la vue
        return $this->render('connexion/connexion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

    