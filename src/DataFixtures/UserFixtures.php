<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('chuck@norris.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '0123'));
        $user->setRoles(["ROLE_ADMIN"]);

        $user2 = new User();
        $user2->setEmail('mickey@mouse.com');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, '0123'));
        $user2->setRoles(["ROLE_USER"]);

        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();
    }
}
