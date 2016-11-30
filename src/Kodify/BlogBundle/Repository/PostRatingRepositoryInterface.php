<?php

/**
 * Created by PhpStorm.
 * User: miquel
 * Date: 7/9/15
 * Time: 2:06.
 */
namespace Kodify\BlogBundle\Repository;

use Kodify\BlogBundle\Entity\Post;

interface PostRatingRepositoryInterface
{
    public function getRatingForPost(Post $post);
}
