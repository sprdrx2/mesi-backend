<?php

namespace App\Controller;

use App\Entity\Venue;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        if(is_null($venue)){
            throw new HttpException(404, "Venue doesn't exist!");
        }

        return $this->json($venue);
    }

    /**
     * @Route("/venue/{yelp_id}", name="create", methods={"POST"})
     */
    public function createAction(Request $request, $yelp_id)
    {
        $exists = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $yelp_id]);

        if(is_null($exists)) {
            $venue = new Venue();
            $venue->setYelpId($yelp_id);
            $jsonVenue = json_decode($request->getContent());

            $venue->setMenuEnfant($jsonVenue->menu_enfant);
            $venue->setEspacePoussette($jsonVenue->espace_poussette);
            $venue->setEspaceJeu($jsonVenue->espace_jeu);
            $venue->setTableLanger($jsonVenue->table_langer);
            $venue->setTableLangerMen($jsonVenue->table_langer_men);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($venue);
            $entityManager->flush();

            return $this->json($venue);

        } else{
            throw new HttpException(400,'Venue already exists!');
        }
    }

    /**
     * @Route("/venue/{yelp_id}", name="update", methods={"PUT"})
     */
    public function updateAction(Request $request, $yelp_id)
    {
        $venue = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $yelp_id]);

        if(is_null($venue)){
            throw new HttpException(404, "Venue doesn't exist!");
        }

        $jsonVenue = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();

        $venue->setMenuEnfant($jsonVenue->menu_enfant);
        $venue->setEspacePoussette($jsonVenue->espace_poussette);
        $venue->setEspaceJeu($jsonVenue->espace_jeu);
        $venue->setTableLanger($jsonVenue->table_langer);
        $venue->setTableLangerMen($jsonVenue->table_langer_men);

        $entityManager->persist($venue);
        $entityManager->flush($venue);

        return $this->json($venue);
    }

    /**
     * @Route("/venue/{yelp_id}", name="delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, $yelp_id)
    {
        $venue = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $yelp_id]);

        if(is_null($venue)){
            throw new HttpException(404, "Venue doesn't exist!");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($venue);
        $entityManager->flush();

        $venues = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findAll();

        return $this->json($venues);
    }
}
