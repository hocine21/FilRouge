<?php
// src/DataFixtures/ClientFixtures.php
namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        // Liste des rôles possibles
        $roles = ['ROLE_PARTICULIER', 'ROLE_PROFESSIONNEL'];

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setNom($faker->lastName);
            $client->setPrenom($faker->firstName);
            $client->setCodePostale($faker->postcode);
            $client->setAdresseEmail($faker->email);
            $client->setNumeroTelephone($faker->phoneNumber);
            $client->setVille($faker->city);
            $client->setNomRue($faker->streetAddress);
            $client->setMotDePasse(password_hash('password', PASSWORD_BCRYPT)); // Mot de passe crypté
            $client->setSiret($faker->optional()->randomNumber(9));
            $client->setRaisonSociale($faker->optional()->company);

            // Attribuer un rôle aléatoire
            $client->setRoles([$roles[array_rand($roles)]]);

            $manager->persist($client);
        }

        $manager->flush();
    }
}
