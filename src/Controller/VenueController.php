<?php

namespace App\Controller;

use App\Entity\Venue;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class VenueController extends AbstractController
{
    /**
     * Route("/venue", name="list", methods={"GET"})
     */
    /*public function listAction()
    {
        $venues = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findAll();

        return $this->json($venues);
    }*/

    /**
     * Route("/venue/{yelp_id}", name="show", methods={"GET"})
     */
    /*public function showAction($yelp_id)
    {
        $venue = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $yelp_id]);

        if(is_null($venue)){
            throw new HttpException(404, "Venue doesn't exist!");
        }

        return $this->json($venue);
    }*/

    /**
     * @Route("/venue/create", name="create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function createAction(Request $request)
    {

    $jsonVenue = json_decode($request->getContent());
	$exists = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $jsonVenue->yelp_id]);

        if(is_null($exists)) {
            $venue = new Venue();
            $venue->setYelpId($jsonVenue->yelp_id);
            $venue->setMenuEnfant($jsonVenue->menuEnfant);
            $venue->setEspacePoussette($jsonVenue->espacePoussette);
            $venue->setEspaceJeu($jsonVenue->espaceJeu);
            $venue->setTableLanger($jsonVenue->tableLanger);
            $venue->setTableLangerMen($jsonVenue->tableLangerMen);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($venue);
            $entityManager->flush();

            return $this->json($venue);

        } else{
            throw new HttpException(400,'Venue already exists!');
        }
    }

    /**
     * @Route("/venue/update", name="update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        $jsonVenue = json_decode($request->getContent());
        $venue = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findOneBy(["yelp_id" => $jsonVenue->yelp_id]);

        if(is_null($venue)){
            throw new HttpException(404, "Venue doesn't exist!");
        }


        $entityManager = $this->getDoctrine()->getManager();

        $venue->setMenuEnfant($jsonVenue->menuEnfant);
        $venue->setEspacePoussette($jsonVenue->espacePoussette);
        $venue->setEspaceJeu($jsonVenue->espaceJeu);
        $venue->setTableLanger($jsonVenue->tableLanger);
        $venue->setTableLangerMen($jsonVenue->tableLangerMen);

        $entityManager->persist($venue);
        $entityManager->flush($venue);

        return $this->json($venue);
    }

    /**
     * @Route("/venue/{yelp_id}", name="delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
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
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
        /*$venues = $this->getDoctrine()
            ->getRepository(Venue::class)
            ->findAll();

        return $this->json($venues);*/
    }

    /**
     * @Route("/venues/compare", name="compare", methods={"POST"})
     */
    public function compareAction(Request $request) {
        $yelpVenues = json_decode($request->getContent(), true);
	$responseArray = []; // /!\ pluriel
	$responseArray["bbFriendlyVenues"] = [];
	$responseArray["bbNotFriendlyVenues"] = [];
	$responseArray["unknownStatusVenues"] = [];

        foreach($yelpVenues as $yelpVenue) {
            $mesiVenueArray = []; // /!\ singulier
            $mesiVenue = $this->getDoctrine()->getRepository(Venue::class)->findOneBy(["yelp_id" => $yelpVenue["id"]]);
            if (is_null($mesiVenue)) {
                $mesiVenueArray["knownStatus"] = False;
                $mesiVenueArray["yelp_id"] = $yelpVenue["id"];
                $mesiVenueArray["espacePoussette"] = False;
                $mesiVenueArray["tableLanger"] = False;
                $mesiVenueArray["tableLangerMen"] = False;
                $mesiVenueArray["menuEnfant"] = False;
                $mesiVenueArray["espaceJeu"] = False;
		$mesiVenueArray["yelpVenue"] = $yelpVenue;
		$mesiVenueArray["bbFriendly"] = null;

		array_push($responseArray["unknownStatusVenues"], $mesiVenueArray);
            } else {
		$mesiVenueArray["knownStatus"] = True;
                $mesiVenueArray["yelp_id"] = $yelpVenue["id"];
                $mesiVenueArray["espacePoussette"] = $mesiVenue->getEspacePoussette();
                $mesiVenueArray["tableLanger"] =  $mesiVenue->getTableLanger();
                $mesiVenueArray["tableLangerMen"] = $mesiVenue->getTableLangerMen();
                $mesiVenueArray["menuEnfant"] = $mesiVenue->getMenuEnfant();
                $mesiVenueArray["espaceJeu"] = $mesiVenue->getEspaceJeu();
		$mesiVenueArray["yelpVenue"] = $yelpVenue;
		$mesiVenueArray["bbFriendly"] = ($mesiVenueArray["espacePoussette"] OR $mesiVenueArray["espaceJeu"] OR  $mesiVenueArray["menuEnfant"] OR$mesiVenueArray["tableLanger"] OR $mesiVenueArray["tableLangerMen"]);

		if ($mesiVenueArray["bbFriendly"] === true) {
			array_push($responseArray["bbFriendlyVenues"], $mesiVenueArray);
		} else {
			array_push($responseArray["bbNotFriendlyVenues"], $mesiVenueArray);	
		}
            }
          
        }

        return $this->json($responseArray);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

}
