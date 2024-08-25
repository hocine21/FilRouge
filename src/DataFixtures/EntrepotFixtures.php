<?php
// src/DataFixtures/EntrepotFixtures.php
namespace App\DataFixtures;

use App\Entity\Entrepot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EntrepotFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer plusieurs instances d'Entrepot avec des données fictives
        $entrepot1 = new Entrepot();
        $entrepot1->setNom('Entrepot A');
        $entrepot1->setVille('Paris');
        $entrepot1->setCodePostale(75001);
        $entrepot1->setRue('1 Rue de Paris');

        $entrepot2 = new Entrepot();
        $entrepot2->setNom('Entrepot B');
        $entrepot2->setVille('Lyon');
        $entrepot2->setCodePostale(69001);
        $entrepot2->setRue('2 Rue de Lyon');

        $entrepot3 = new Entrepot();
        $entrepot3->setNom('Entrepot C');
        $entrepot3->setVille('Marseille');
        $entrepot3->setCodePostale(13001);
        $entrepot3->setRue('3 Rue de Marseille');

        // Persister les entités
        $manager->persist($entrepot1);
        $manager->persist($entrepot2);
        $manager->persist($entrepot3);

        // Enregistrer les changements dans la base de données
        $manager->flush();
    }
}
