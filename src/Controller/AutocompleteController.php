<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AutocompleteController extends AbstractController
{
    private $yelpApiKey = 'VUgnEj3HGTTbpvVzNh_gChrFdhnn9Gw75jKm761Hlel0tzsF57f3jdptFHQEtO5C7pBjzndUmIcv0S1C7eZh_-9TCI5m5JKVqwB7rFCQDu1ztwvwxjK1Sqs6OJQsXXYx';
    private $yelpApiAddress = 'https://corsanywhere.herokuapp.com/https://api.yelp.com/v3/autocomplete';

    /**
     * @Route("/autocomplete/${q}", name="autocomplete")
     */
    public function autocomplete(String $query) {

    }
}
