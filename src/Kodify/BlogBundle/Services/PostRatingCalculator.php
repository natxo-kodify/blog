<?php

/**
 * Created by PhpStorm.
 * User: miquel
 * Date: 7/9/15
 * Time: 2:03.
 */
namespace Kodify\BlogBundle\Services;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Repository\PostRatingRepositoryInterface;

class PostRatingCalculator
{
    private $postRatingRepository;

    public function __construct(PostRatingRepositoryInterface $postRatingRepository)
    {
        $this->postRatingRepository = $postRatingRepository;
    }

    public function getRatingForPost(Post $post)
    {
        return $this->postRatingRepository->getRatingForPost($post);
    }
}
