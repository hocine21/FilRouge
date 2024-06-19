<?php

namespace App\DataFixtures;

use App\Entity\Entrepot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EntrepotFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer 10 entrepôts avec des données fictives
        for ($i = 1; $i <= 10; $i++) {
            $entrepot = new Entrepot();
            $entrepot->setNom('Entrepot ' . $i)
                ->setVille('Ville ' . $i)
                ->setCodePostale(10000 + $i)
                ->setRue('Rue ' . $i);

            $manager->persist($entrepot);
        }

        // Enregistrer tous les objets créés dans la base de données
        $manager->flush();
    }
}
