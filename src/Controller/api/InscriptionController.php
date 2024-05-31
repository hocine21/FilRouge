<?php

namespace App\Controller\api;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InscriptionController extends AbstractController
{
    #[Route('/api/inscription', name: 'api_inscription', methods: ['POST'])]
    public function inscription(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données requises
        $requiredFields = ['Nom', 'Prenom', 'CodePostale', 'AdresseEmail', 'NumeroTelephone', 'Ville', 'NomRue', 'MotDePasse', 'Roles'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return new JsonResponse(['error' => 'Tous les champs doivent être renseignés.'], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        // Validation de l'adresse e-mail
        if (!filter_var($data['AdresseEmail'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'L\'adresse e-mail n\'est pas valide.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Création d'un nouvel objet Client
        $client = new Client();
        $client->setMotDePasse(password_hash($data['MotDePasse'], PASSWORD_DEFAULT));
        $client->setNom($data['Nom']);
        $client->setPrenom($data['Prenom']);
        $client->setCodePostale($data['CodePostale']);
        $client->setAdresseEmail($data['AdresseEmail']);
        $client->setNumeroTelephone($data['NumeroTelephone']);
        $client->setVille($data['Ville']);
        $client->setNomRue($data['NomRue']);

        if ($data['Roles'] === 'ROLE_PROFESSIONNEL') {
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

        // Validation du client
        $errors = $validator->validate($client);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['error' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Enregistrement du client dans la base de données
        $entityManager->persist($client);
        $entityManager->flush();

        // Envoi de l'e-mail de confirmation
        try {
            $email = (new Email())
                ->from('hello@example.com')
                ->to($client->getAdresseEmail())
                ->subject('Confirmation d\'inscription')
                ->text('Bonjour ' . $client->getPrenom() . ', votre inscription a été confirmée.');

            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de l\'envoi de l\'e-mail de confirmation.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Réponse de succès
        return new JsonResponse(['message' => 'Inscription réussie. Un e-mail de confirmation a été envoyé à ' . $client->getAdresseEmail()], JsonResponse::HTTP_CREATED);
    }
}
