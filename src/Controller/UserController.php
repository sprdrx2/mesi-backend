<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @Route("/user/testlogin", name="login")
     * @IsGranted("ROLE_USER")
     */
    public function userLoginAction()
    {
        $arr = ['userIsLoggued' => 'OK' ];
        return $this->json(json_encode($arr));
    }

    /**
     * @Route("/user/signin", name="signin", methods={"POST"})
     */
    public function userSignInAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $data = json_decode($request->getContent(), true);
        $user->setEmail($data["email"]);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $data["password"]));
        $user->setRoles(["ROLE_USER"]);
        $user->setActif(false);

        $entityManager->persist($user);
        $entityManager->flush();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }


}
