<?php

/**
 * Created by PhpStorm.
 * User: miquel
 * Date: 6/9/15
 * Time: 22:10.
 */
namespace Kodify\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Kodify\BlogBundle\Entity\Post;

class PostRatingRepository extends EntityRepository
{
    public function getRatingForPost(Post $post)
    {
        return $this->createQueryBuilder('p')
            ->select('AVG(p.rating)')
            ->where('p.post = :post')
            ->setParameter('post', $post)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
