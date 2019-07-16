<?php

namespace App\Controller;

use App\Entity\Venue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VenueController extends AbstractController
{
    /**
     * @Route("/venue", name="venue")
     */
    public function listAction()
    {
        $venues = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findAll();
        return $this->json($venues);
    }
}
