<?php

namespace App\DataFixtures;

use App\Entity\Venue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpClient\HttpClient;

/*
 *  Fais une recherche sur l'API de YELP et insère aléatoirement les lieux récupérés dans notre BDD. Avec des infos aléatoires.
 */

class RandomYelpFixtures extends Fixture
{
    private $yelpApiKey = 'VUgnEj3HGTTbpvVzNh_gChrFdhnn9Gw75jKm761Hlel0tzsF57f3jdptFHQEtO5C7pBjzndUmIcv0S1C7eZh_-9TCI5m5JKVqwB7rFCQDu1ztwvwxjK1Sqs6OJQsXXYx';
    private $yelpApiAddress = 'https://corsanywhere.herokuapp.com/https://api.yelp.com/v3/businesses/search';
    private $targetLocation = 'LYON';

    private $yelpVenuesTotal        = 0;
    private $yelpVenuesInserted     = 0;
    private $yelpVenuesBBFriendly   = 0;

    private $yelpVenues = [];

    private function getYelpVenues() {
        $httpClient = HttpClient::create(['headers' => [
            'Authorization' => 'Bearer ' . $this->yelpApiKey,
        ]]);
        $httpResponse = $httpClient->request('GET', $this->yelpApiAddress . "?&categories=bars,restaurant&location=" . $this->targetLocation);
        $jsonResponse = json_decode($httpResponse->getContent(), TRUE);
        $this->yelpVenuesTotal = $jsonResponse["total"];
        $offset = 0;
        while ($offset < $this->yelpVenuesTotal) {
            $httpResponse = $httpClient->request('GET', $this->yelpApiAddress . "?&categories=bars,restaurant&location=" . $this->targetLocation);
            array_push($this->yelpVenues, json_decode($httpResponse->getContent(), TRUE)['businesses']);
            $offset = $offset + 50;
        }
    }

    public function load(ObjectManager $manager)
    {
        $this->getYelpVenues();

        foreach($this->yelpVenues as $yelpVenueArray) {
            foreach ($yelpVenueArray as $yelpVenue) {

                if (rand(0, 1)) {
                    $this->yelpVenuesInserted++;
                    $venue = new Venue();
                    $venue->setYelpId($yelpVenue['id']);
                    $venue->setEspaceJeu((bool)rand(0, 1));
                    $venue->setEspacePoussette((bool)rand(0, 1));
                    $venue->setTableLanger((bool)rand(0, 1));
                    $venue->setTableLangerMen((bool)rand(0, 1));
                    $venue->setMenuEnfant((bool)rand(0, 1));
                    $manager->persist($venue);

                    if ($venue->getEspaceJeu() OR $venue->getEspacePoussette() OR $venue->getMenuEnfant() OR $venue->getTableLanger() OR $venue->getTableLangerMen()) {
                        $this->yelpVenuesBBFriendly++;
                    }
                }
            }
        }

        $manager->flush();
        echo 'YelpVenues récupérés: ' . $this->yelpVenuesTotal . "\n";
        echo 'YelpVenues insérées: ' . $this->yelpVenuesInserted . "\n";
        echo 'YelpVenues BB Friendly: ' . $this->yelpVenuesBBFriendly . "\n";
    }
}
