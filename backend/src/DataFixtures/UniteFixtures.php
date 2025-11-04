<?php

namespace App\DataFixtures;

use App\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UniteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = ['Unité', 'Forfait', 'Annuel', 'Semestre'];
        foreach ($types as $t) {
            $u = new Unite();
            $u->setType($t);
            $manager->persist($u);
        }
        $manager->flush();
    }
}


