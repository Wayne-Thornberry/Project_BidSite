<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $user->setUsername("User");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user'
        ));
        $user->setRoles( ["ROLE_USER"]);


        $admin = new User();
        $admin->setUsername("Admin");
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'admin'
        ));
        $admin->setRoles( ["ROLE_ADMIN"]);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();
    }
}
