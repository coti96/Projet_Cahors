<?php

namespace App\DataFixtures;

use App\Entity\Serveur;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ServeursFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {   
        for($i= 1; $i <= 10; $i++)
        {
            $serveur = new Serveur();
            $serveur->setNom("Nom du serveur n°$i");
            $serveur->setEmplacement("Salle n°1");
            $serveur->setMarque('HP');
            $serveur->setRack("$i");
            $serveur->setDate(18/10/2022);
            $manager->persist($serveur);
        }
        $manager->flush();
    }
}

