<?php

namespace App\Controller\api;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscriptionParticulier', name: 'app_inscriptionParticulier', methods: ['GET'])]
    public function index(): Response 
    {
        return $this->render('inscription/inscriptionParticulier.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }

    #[Route('/inscriptionProfessionnel', name: 'app_inscriptionProfessionnel', methods: ['GET'])]
    public function pro(): Response 
    {
        return $this->render('inscription/inscriptionProfessionnel.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }

    #[Route('/api/inscription', name: 'api_inscription', methods: ['POST'])]
    public function inscription(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, MailerInterface $mailer): JsonResponse
    {
        // Récupérer les données de la requête JSON
        $data = json_decode($request->getContent(), true);

        // Vérifier si toutes les données nécessaires sont présentes
        $requiredFields = ['Nom', 'Prenom', 'CodePostale', 'AdresseEmail', 'NumeroTelephone', 'Ville', 'NomRue', 'MotDePasse', 'Roles'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return new JsonResponse(['error' => 'Tous les champs doivent être renseignés.'], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        // Vérifier le format de l'e-mail
        if (!filter_var($data['AdresseEmail'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'L\'adresse e-mail n\'est pas valide.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier si l'e-mail existe déjà dans la base de données
        $existingClient = $entityManager->getRepository(Client::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
        if ($existingClient !== null) {
            return new JsonResponse(['error' => 'Cette adresse e-mail est déjà utilisée.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier le format du mot de passe
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $data['MotDePasse'])) {
            return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins 12 caractères, dont une majuscule, une minuscule, un chiffre et un symbole.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de Client
        $client = new Client();

        // Hasher le mot de passe avant de l'enregistrer
        $client->setMotDePasse(password_hash($data['MotDePasse'], PASSWORD_DEFAULT));

        // Assigner les autres données
        $client->setNom($data['Nom']);
        $client->setPrenom($data['Prenom']);
        $client->setCodePostale($data['CodePostale']);
        $client->setAdresseEmail($data['AdresseEmail']);
        $client->setNumeroTelephone($data['NumeroTelephone']);
        $client->setVille($data['Ville']);
        $client->setNomRue($data['NomRue']);

        // Vérifier et assigner le rôle
        if ($data['Roles'] === 'ROLE_PROFESSIONNEL') {
            // Vérifier si SIRET et Raison Sociale sont présents
            if (!isset($data['Siret']) || !isset($data['RaisonSociale'])) {
                return new JsonResponse(['error' => 'Les champs Siret et RaisonSociale sont obligatoires pour les professionnels.'], JsonResponse::HTTP_BAD_REQUEST);
            }
            $client->setRoles('ROLE_PROFESSIONNEL');
            $client->setSiret($data['Siret']);
            $client->setRaisonSociale($data['RaisonSociale']);
        } elseif ($data['Roles'] === 'ROLE_PARTICULIER') {
            $client->setRoles('ROLE_PARTICULIER');
        } else {
            return new JsonResponse(['error' => 'Le rôle doit être spécifié comme "ROLE_PROFESSIONNEL" ou "ROLE_PARTICULIER".'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Valider l'entité Client
        $errors = $validator->validate($client);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['error' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Enregistrer le nouveau client dans la base de données
        $entityManager->persist($client);
        $entityManager->flush();

        // Envoi de l'e-mail de confirmation
        try {
            $email = (new Email())
                ->from('fff9868a57-e3828d@inbox.mailtrap.io')
                ->to($client->getAdresseEmail())
                ->subject('Confirmation d\'inscription')
                ->text('Bonjour ' . $client->getPrenom() . ', votre inscription a été confirmée.');

            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de l\'envoi de l\'e-mail de confirmation.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Inscription réussie. Un e-mail de confirmation a été envoyé à ' . $client->getAdresseEmail()], JsonResponse::HTTP_CREATED);
    }
}
