<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Doctrine\Common\Persistence\ManagerRegistry;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Follower;
use App\Entity\User;
use App\Entity\Post;


class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('followed', [$this, 'doSomething']),
            new TwigFunction('following', [$this, 'follow_user']),
            new TwigFunction('check', [$this, 'check_user']),
            new TwigFunction('tweet_count', [$this, 'tweet_count']),
            new TwigFunction('following_count', [$this, 'following_count']),
            new TwigFunction('follower_count', [$this, 'follower_count']),
            new TwigFunction('search_id', [$this, 'search_id'])
        ];
    }
    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry;
    }
    public function doSomething($id, $admin)
    {
        // ...
        $follower = $this->em->getRepository(Follower::class)->findBy([
            'followed_user' => $id,
            'user' => $admin
        ]);

        if(sizeof($follower)>0) return false;
        else return true;

    }

    public function follow_user($id)
    {
        // ...
        $follower = $this->em->getRepository(User::class)->findBy([
            'id' => $id,
        ]);   
        return $follower;

    }

    public function check_user($admin_id, $user_id)
    {
        // ...
        $follower = $this->em->getRepository(Follower::class)->findBy([
            'followed_user' => $user_id,
            'user' => $admin_id
            
        ]);   
        if($follower) return true;
        else return false;

    }

    public function tweet_count($user_id)
    {
        // ...
        $count = $this->em->getRepository(Post::class)->findBy([
            'user' => $user_id
            
        ]);   
        return $count;

    }

    public function follower_count($user_id)
    {
        // ...
        $count = $this->em->getRepository(Follower::class)->findBy([
            'followed_user' => $user_id
            
        ]);   
        return $count;

    }

    public function following_count($user_id)
    {
        // ...
        $count = $this->em->getRepository(Follower::class)->findBy([
            'user' => $user_id
            
        ]);   
        return $count;

    }

    public function search_id($admin_id, $user_id)
    {
        // ...
        $follower = $this->em->getRepository(Follower::class)->findBy([
            'followed_user' => $user_id,
            'user' => $admin_id
            
        ]);   
        return $follower;

    }
}
