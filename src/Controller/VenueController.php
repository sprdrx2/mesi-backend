<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VenueController extends AbstractController
{
    /**
     * @Route("/venue", name="venue")
     */
    public function index()
    {
        return $this->render('venue/index.html.twig', [
            'controller_name' => 'VenueController',
        ]);
    }
}
