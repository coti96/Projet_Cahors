<?php

namespace App\DataFixtures;

use App\Entity\Routeur;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RouteursFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i= 1; $i <= 5; $i++)
        { 
            $routeur = new Routeur();
            $routeur->setNom("Nom du ROUTEUR n°$i");
            $routeur->setEmplacement("Salle n°1");
            $routeur->setMarque('HP');
            $routeur->setRack("$i");
            $routeur->setDate(21/10/2022);
            $manager->persist($routeur);
        }
        $manager->flush();
    }
}
