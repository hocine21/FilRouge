<?php

namespace App\Controller\api;

use App\Entity\Client;
use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion', methods: ['POST'])]
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
            $client = $entityManager->getRepository(Client::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
            if ($client !== null && $passwordHasher->isPasswordValid($client, $data['MotDePasse'])) {
                // Construire la réponse avec les informations du client
                $response = [
                    'message' => 'Connexion réussie en tant que client',
                    'user' => [
                        'id' => $client->getId(),
                        'nom' => $client->getNom(),
                        'prenom' => $client->getPrenom(),
                        'adresse_email' => $client->getAdresseEmail(),
                        'roles' => $client->getRoles()
                    ]
                ];
                return $this->json($response, 200);
            }

            // Vérifier si l'utilisateur est un employé
            $employe = $entityManager->getRepository(Employe::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
            if ($employe !== null && $passwordHasher->isPasswordValid($employe, $data['MotDePasse'])) {
                // Construire la réponse avec les informations de l'employé
                $response = [
                    'message' => 'Connexion réussie en tant qu\'employé',
                    'user' => [
                        'id' => $employe->getId(),
                        'nom' => $employe->getNom(),
                        'prenom' => $employe->getPrenom(),
                        'adresse_email' => $employe->getAdresseEmail(),
                        'roles' => $employe->getRoles()
                    ]
                ];
                return $this->json($response, 200);
            }

            // Si l'utilisateur n'est pas connecté ou les informations sont incorrectes, renvoyer une erreur d'authentification
            throw new AuthenticationException('Adresse e-mail ou mot de passe incorrect.');

        } catch (AuthenticationException $e) {
            return $this->json(['error' => $e->getMessage()], 401);
        }
    }
}
