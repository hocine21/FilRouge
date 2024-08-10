<?php

namespace App\Controller\api;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InscriptionEmployeController extends AbstractController
{
    #[Route('/api/inscription/employe', name: 'api_inscription_employe', methods: ['POST'])]
    public function inscription(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, MailerInterface $mailer): JsonResponse
    {
        // Récupérer les données de la requête JSON
        $data = json_decode($request->getContent(), true);

        // Vérifier si toutes les données nécessaires sont présentes
        $requiredFields = ['Nom', 'Prenom', 'AdresseEmail', 'MotDePasse', 'Roles'];
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
        $existingEmploye = $entityManager->getRepository(Employe::class)->findOneBy(['AdresseEmail' => $data['AdresseEmail']]);
        if ($existingEmploye !== null) {
            return new JsonResponse(['error' => 'Cette adresse e-mail est déjà utilisée.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier le format du mot de passe
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $data['MotDePasse'])) {
            return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins 12 caractères, dont une majuscule, une minuscule, un chiffre et un symbole.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance d'Employe
        $employe = new Employe();

        // Hasher le mot de passe avant de l'enregistrer
        $employe->setMotDePasse(password_hash($data['MotDePasse'], PASSWORD_DEFAULT));

        // Assigner les autres données
        $employe->setNom($data['Nom']);
        $employe->setPrenom($data['Prenom']);
        $employe->setAdresseEmail($data['AdresseEmail']);

        // Assigner les rôles
        if (is_string($data['Roles'])) {
            $roles = explode(',', $data['Roles']);
        } else {
            $roles = $data['Roles'];  // Si c'est déjà un tableau
        }
        $employe->setRoles($roles);

        // Valider l'entité Employe
        $errors = $validator->validate($employe);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['error' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Enregistrer le nouvel employé dans la base de données
        $entityManager->persist($employe);
        $entityManager->flush();

        // Envoi de l'e-mail de confirmation
        try {
            $email = (new Email())
                ->from('fff9868a57-e3828d@inbox.mailtrap.io')
                ->to($employe->getAdresseEmail())
                ->subject('Confirmation d\'inscription')
                ->text('Bonjour ' . $employe->getPrenom() . ', votre inscription a été confirmée.');

            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse(['error' => 'Une erreur est survenue lors de l\'envoi de l\'e-mail de confirmation.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Inscription réussie. Un e-mail de confirmation a été envoyé à ' . $employe->getAdresseEmail()], JsonResponse::HTTP_CREATED);
    }
}
