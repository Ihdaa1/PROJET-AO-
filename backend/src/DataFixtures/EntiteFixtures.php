<?php

namespace App\DataFixtures;

use App\Entity\Entite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EntiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rows = [
            ['nom' => 'DMP', 'direction' => 'Informatique', 'responsable' => 'Mme X', 'abreviation' => 'DMP'],
            ['nom' => 'DAF', 'direction' => 'Finances', 'responsable' => 'M. Y', 'abreviation' => 'DAF'],
            ['nom' => 'DRH', 'direction' => 'Ressources Humaines', 'responsable' => 'Mme Z', 'abreviation' => 'DRH'],
        ];

        foreach ($rows as $r) {
            $e = new Entite();
            $e->setNom($r['nom']);
            $e->setDirection($r['direction']);
            $e->setResponsable($r['responsable']);
            $e->setAbreviation($r['abreviation']);
            $manager->persist($e);
        }

        $manager->flush();
    }
}


