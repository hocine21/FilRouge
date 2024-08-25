<?php
// tests/Controller/api/InscriptionControllerTest.php
namespace App\Tests\Controller\api;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class InscriptionControllerTest extends WebTestCase
{
    public function testInscriptionWithMissingFields()
    {
        $client = static::createClient();

        $client->request('POST', '/api/inscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'Nom' => 'Doe',
            'Prenom' => 'John',
            // 'AdresseEmail' is missing here
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Tous les champs doivent être renseignés.']),
            $client->getResponse()->getContent()
        );
    }

    public function testInscriptionWithInvalidEmail()
    {
        $client = static::createClient();

        $client->request('POST', '/api/inscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'Nom' => 'Doe',
            'Prenom' => 'John',
            'AdresseEmail' => 'invalid-email',
            'MotDePasse' => 'ValidPass123!',
            'CodePostale' => '12345',
            'NumeroTelephone' => '0123456789',
            'Ville' => 'Paris',
            'NomRue' => '123 Rue Imaginaire',
            'Roles' => 'ROLE_PARTICULIER'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'L\'adresse e-mail n\'est pas valide.']),
            $client->getResponse()->getContent()
        );
    }

    public function testInscriptionWithExistingEmail()
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        // Créer un client fictif avec une adresse e-mail
        $existingClient = new Client();
        $existingClient->setNom('Doe');
        $existingClient->setPrenom('John');
        $existingClient->setAdresseEmail('existing@example.com');
        $existingClient->setMotDePasse(password_hash('ValidPass123!', PASSWORD_DEFAULT));
        $entityManager->persist($existingClient);
        $entityManager->flush();

        // Essayer de créer un nouveau client avec la même adresse e-mail
        $client->request('POST', '/api/inscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'Nom' => 'Doe',
            'Prenom' => 'Jane',
            'AdresseEmail' => 'existing@example.com',
            'MotDePasse' => 'ValidPass123!',
            'CodePostale' => '12345',
            'NumeroTelephone' => '0123456789',
            'Ville' => 'Paris',
            'NomRue' => '123 Rue Imaginaire',
            'Roles' => 'ROLE_PARTICULIER'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Cette adresse e-mail est déjà utilisée.']),
            $client->getResponse()->getContent()
        );
    }

    public function testInscriptionWithInvalidPassword()
    {
        $client = static::createClient();

        $client->request('POST', '/api/inscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'Nom' => 'Doe',
            'Prenom' => 'John',
            'AdresseEmail' => 'john.doe@example.com',
            'MotDePasse' => 'weakpass',
            'CodePostale' => '12345',
            'NumeroTelephone' => '0123456789',
            'Ville' => 'Paris',
            'NomRue' => '123 Rue Imaginaire',
            'Roles' => 'ROLE_PARTICULIER'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Le mot de passe doit contenir au moins 12 caractères, dont une majuscule, une minuscule, un chiffre et un symbole.']),
            $client->getResponse()->getContent()
        );
    }

    public function testSuccessfulInscriptionForParticulier()
    {
        $client = static::createClient();

        $client->request('POST', '/api/inscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'Nom' => 'Doe',
            'Prenom' => 'John',
            'AdresseEmail' => 'john.doe@example.com',
            'MotDePasse' => 'ValidPass123!',
            'CodePostale' => '12345',
            'NumeroTelephone' => '0123456789',
            'Ville' => 'Paris',
            'NomRue' => '123 Rue Imaginaire',
            'Roles' => 'ROLE_PARTICULIER'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Inscription réussie. Un e-mail de confirmation a été envoyé à john.doe@example.com']),
            $client->getResponse()->getContent()
        );
    }

    public function testSuccessfulInscriptionForProfessionnel()
    {
        $client = static::createClient();

        $client->request('POST', '/api/inscription', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'Nom' => 'Doe',
            'Prenom' => 'Jane',
            'AdresseEmail' => 'jane.doe@example.com',
            'MotDePasse' => 'ValidPass123!',
            'CodePostale' => '12345',
            'NumeroTelephone' => '0123456789',
            'Ville' => 'Paris',
            'NomRue' => '123 Rue Imaginaire',
            'Roles' => 'ROLE_PROFESSIONNEL',
            'Siret' => '12345678901234',
            'RaisonSociale' => 'Doe Enterprises'
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Inscription réussie. Un e-mail de confirmation a été envoyé à jane.doe@example.com']),
            $client->getResponse()->getContent()
        );
    }
}
