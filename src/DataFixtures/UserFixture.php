<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('Admin');
        $user->setUsername('admin');

        $user->setPassword(
            $this->encoder->encodePassword($user, '0000')
        );
        
        $user->setEmail('no-reply@bk.com');
        $post = new Post();
        $post->setDescription('The leading PHP framework to create websites and web applications. Built on top of the Symfony Components.');
        $post->setUser($user);

        $manager->persist($post);
        $manager->persist($user);

        $manager->flush();
    }
}
