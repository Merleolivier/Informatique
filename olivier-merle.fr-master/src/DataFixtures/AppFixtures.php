<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User;
        $hash = $this->encoder->encodePassword($user, "test");
        $user->setUsername("charles.sauvat")
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($hash);
        $manager->persist($user);
        $manager->flush();
    }
}
