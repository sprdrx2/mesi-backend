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

    private $yelpVenuesTotal        = 0;
    private $yelpVenuesInserted     = 0;
    private $yelpVenuesBBFriendly   = 0;

    private $yelpVenuesTotalAllCity        = 0;
    private $yelpVenuesInsertedAllCity     = 0;
    private $yelpVenuesBBFriendlyAllCity   = 0;

    private $cityTotal = 0;

    private $yelpVenues = [];

    private $manager;

    private function getYelpVenues($city) {
	$this->yelpVenues = [];
        $httpClient = HttpClient::create(['headers' => [
            'Authorization' => 'Bearer ' . $this->yelpApiKey,
        ]]);
        $httpResponse = $httpClient->request('GET', $this->yelpApiAddress . "?&categories=bars,restaurant&location=" . $city);
        $jsonResponse = json_decode($httpResponse->getContent(), TRUE);
	$this->yelpVenuesTotal = $jsonResponse["total"];
	echo "nombre de resultats yelp: ". $this->yelpVenuesTotal ."\n";
        $offset = 0;
        while ($offset < $this->yelpVenuesTotal and $offset < 1000) {
            $httpResponse = $httpClient->request('GET', $this->yelpApiAddress . "?&radius=10000&categories=bars,restaurant&location=" . $city."&offset=".$offset."&limit=50");
           foreach( json_decode($httpResponse->getContent(), TRUE)['businesses'] as $yvArr) {
		$this->yelpVenues[] = $yvArr;
	   }
            $offset = $offset + 50;
        }
    }

    public function loadCity($city)
    {
        $this->getYelpVenues($city);
	//echo '$this->yelpVenues: ' . count($this->yelpVenues);
	$compteur = 0;
	    foreach ($this->yelpVenues as $yelpVenue) {
	    $compteur++;
	    if (($compteur % 2) === 0) {
		    //echo "creation d'un record pour item ".$compteur."/".$this->yelpVenuesTotal."\n";
                    $this->yelpVenuesInserted++;
		   
		    if (($compteur % 4) === 0) {
		    	$venue = new Venue();
                    	$venue->setYelpId($yelpVenue['id']);
                    	$venue->setEspaceJeu((bool)rand(0, 1));
                    	$venue->setEspacePoussette((bool)rand(0, 1));
                    	$venue->setTableLanger((bool)rand(0, 1));
                    	$venue->setTableLangerMen((bool)rand(0, 1));
                    	$venue->setMenuEnfant((bool)rand(0, 1));
                    	$this->manager->persist($venue);
		    } else {
			$venue = new Venue();
                    	$venue->setYelpId($yelpVenue['id']);
                    	$venue->setEspaceJeu(false);
                    	$venue->setEspacePoussette(false);
                    	$venue->setTableLanger(false);
                    	$venue->setTableLangerMen(false);
                    	$venue->setMenuEnfant(false);
                    	$this->manager->persist($venue);	
		    }	    
                    if ($venue->getEspaceJeu() OR $venue->getEspacePoussette() OR $venue->getMenuEnfant() OR $venue->getTableLanger() OR $venue->getTableLangerMen()) {
                        $this->yelpVenuesBBFriendly++;
                    }
                }
            }
        

        echo 'YelpVenues récupérés: ' . $this->yelpVenuesTotal . "\n";
        echo 'YelpVenues insérées: ' . $this->yelpVenuesInserted . "\n";
        echo 'YelpVenues BB Friendly: ' . $this->yelpVenuesBBFriendly . "\n";
    }

    public function load(ObjectManager $manager) {
        $this->manager = $manager;
	//include('./src/DataFixtures/villesFrance.array.php');
	$villesFrance = ["Lyon", "Paris", "Marseille"];
	$this->cityTotal = count($villesFrance);
	echo "Villes à traiter: $this->cityTotal\n";
	$compteur = 0;
	foreach($villesFrance as $city) {
		$compteur++;
		echo "Loading fixtures for $city ($compteur/$this->cityTotal).\n";
		$this->loadCity($city);
		$this->yelpVenuesTotalAllCity        += $this->yelpVenuesTotal;
    		$this->yelpVenuesInsertedAllCity     += $this->yelpVenuesInserted; 
    		$this->yelpVenuesBBFriendlyAllCity   += $this->yelpVenuesBBFriendly;
		$this->yelpVenuesTotal        = 0;
		$this->yelpVenuesInserted     = 0;
    		$this->yelpVenuesBBFriendly   = 0;

	}	
	$manager->flush();
	echo "Villes traitées: $this->cityTotal\n";
        echo 'TOTAL YelpVenues récupérés: ' . $this->yelpVenuesTotalAllCity . "\n";
        echo 'TOTAL YelpVenues insérées: ' . $this->yelpVenuesInsertedAllCity . "\n";
        echo 'TOTAL YelpVenues BB Friendly: ' . $this->yelpVenuesBBFriendlyAllCity . "\n";
    }
}
