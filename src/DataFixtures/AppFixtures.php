<?php

namespace App\DataFixtures;

use App\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $yelp_ids = ["la-mère-jean-lyon-2",
            "l-épicerie-lyon-7",
            "au-petit-bouchon-chez-georges-lyon-3",
            "petit-ogre-lyon-2",
            "le-kitchen-café-lyon",
            "restaurant-de-l-institut-paul-bocuse-lyon",
            "brasserie-georges-lyon",
            "chez-mounier-lyon",
            "l-ourson-qui-boit-lyon",
            "l-osteria-de-lello-lyon"];
        foreach($yelp_ids as $yelp_id)
        {
            $venue = new Venue();
            $venue->setYelpId($yelp_id);
            $venue->setEspaceJeu((bool)rand(0,1));
            $venue->setEspacePoussette((bool)rand(0,1));
            $venue->setTableLanger((bool)rand(0,1));
            $venue->setTableLangerMen((bool)rand(0,1));
            $venue->setMenuEnfant((bool)rand(0,1));
            $manager->persist($venue);
        }


        $manager->flush();
    }
}
