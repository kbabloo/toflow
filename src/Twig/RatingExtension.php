<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Doctrine\Common\Persistence\ManagerRegistry;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Rating;

class RatingExtension extends AbstractExtension
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
            new TwigFunction('check_like', [$this, 'check_like']),
            new TwigFunction('like_count', [$this, 'like_count']),
            new TwigFunction('retweet_count', [$this, 'retweet_count']),
        ];
    }

    private $em;

    public function __construct(ManagerRegistry $registry)
    {
        $this->em = $registry;
    }

    public function check_like($admin_id, $post_id)
    {
        $like = $this->em->getRepository(Rating::class)->findBy([
            'user' => $admin_id,
            'post' => $post_id
        ]);
        if(sizeof($like)>0) return false;
        else return true;
    }

    public function like_count($post_id)
    {
        $like = $this->em->getRepository(Rating::class)->findBy([
            'post' => $post_id
        ]);
        return $like;
    }

    public function retweet_count($post_id)
    {
        $retweet = $this->em->getRepository(Post::class)->findBy([
            'repost' => $post_id
        ]);
        return $retweet;
    }
}
