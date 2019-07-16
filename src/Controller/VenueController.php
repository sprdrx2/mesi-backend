<?php

namespace App\Controller;

use App\Entity\Venue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VenueController extends AbstractController
{
    /**
     * @Route("/venue", name="list", methods={"GET"})
     */
    public function listAction()
    {
        $venues = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findAll();

        return $this->json($venues);
    }

    /**
     * @Route("/venue/{yelp_id}", name="show", methods={"GET"})
     */
    public function showAction($yelp_id)
    {
        $venue = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $yelp_id]);

        return $this->json($venue);
    }
}
