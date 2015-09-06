<?php

/**
 * Created by PhpStorm.
 * User: miquel
 * Date: 6/9/15
 * Time: 23:02.
 */
namespace Kodify\BlogBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\PostRating;

class PostRater
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * PostRater constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function rate(Post $post, $rating)
    {
        $postRating = new PostRating($post, $rating);

        $this->objectManager->persist($postRating);
        $this->objectManager->flush();
    }
}
