<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Doctrine\Common\Persistence\ManagerRegistry;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Post;
use App\Entity\Follower;
use App\Entity\User;

class FindExtension extends AbstractExtension
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
            new TwigFunction('check_following', [$this, 'check_following']),
            new TwigFunction('check_user', [$this, 'check_user']),
            new TwigFunction('check_follower', [$this, 'check_follower'])
        ];
    }

    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry;
    }

    public function check_following($admin_id, $user_id)
    {
        $follower = $this->em->getRepository(Follower::class)->findBy([
            'user' => $admin_id,
            'followed_user' => $user_id
        ]);
        if($follower) return true;
        else false; 
    }

    public function check_user($user_id)
    {
        $follower = $this->em->getRepository(User::class)->find($user_id);
        return $follower;
    }

    public function check_follower($user_id)
    {
        $follower = $this->em->getRepository(Follower::class)->findBy([
            'followed_user' => $user_id
        ]);
        return $follower;
    }
}
