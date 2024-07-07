<?php
namespace App\Controller\api;

use App\Entity\Client;
use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ConnexionController extends AbstractController
{
    #[Route('/api/connexion', name: 'connexion', methods: ['POST'])]
    public function connexion(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Récupérer les données de la requête JSON
            $data = json_decode($request->getContent(), true);

            // Vérifier si les champs nécessaires sont présents
            $requiredFields = ['AdresseEmail', 'MotDePasse'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return $this->json(['error' => 'Tous les champs doivent être renseignés.'], 400);
                }
            }

            // Vérifier si l'utilisateur est un client
            $user = $entityManager->getRepository(Client::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
            if ($user === null) {
                // Vérifier si l'utilisateur est un employé
                $user = $entityManager->getRepository(Employe::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
            }

            // Si aucun utilisateur n'est trouvé
            if ($user === null) {
                throw new AuthenticationException('Adresse e-mail ou mot de passe incorrect.');
            }

            // Vérifier si le mot de passe est valide
            if (!$passwordHasher->isPasswordValid($user, $data['MotDePasse'])) {
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

            // Construire la réponse avec les informations de l'utilisateur et le jeton
            $response = [
                'message' => 'Connexion réussie',
                'user' => [
                    'id' => $user->getId(),
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'adresse_email' => $user->getAdresseEmail(),
                    'roles' => $user->getRoles()
                ],
                'access_token' => $token
            ];

            return $this->json($response, 200);

        } catch (\Throwable $e) {
            // Capturer toute exception générée pendant le processus d'authentification
            return $this->json(['error' => $e->getMessage()], 401);
        }
    }
}
