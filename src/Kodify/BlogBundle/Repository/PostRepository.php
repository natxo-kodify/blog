<?php

namespace Kodify\BlogBundle\Repository;

use Kodify\BlogBundle\Entity\Post;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends AbstractBaseRepository
{

    /**
     *  Updates Rate's value of a post
     *
     * @param Post $post
     * @param int $stars
     */
    public function setRate(Post $post, $stars)
    {

        $rated = false;
        if (Post::MINRATE <= $stars && Post::MAXRATE >= $stars) {
            $post->setRateClicks($post->getRateClicks() + 1);
            $post->setRateTotal($post->getRateTotal() + $stars);
            $post->setRate(floor($post->getRateTotal() / $post->getRateClicks()));
            $this->getEntityManager()->flush($post);
            $rated = true;
        }

        return $rated;
    }
}
