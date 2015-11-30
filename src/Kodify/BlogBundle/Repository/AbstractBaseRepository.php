<?php

namespace Kodify\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Kodify\BlogBundle\Entity\AbstractBaseEntity;

/**
 * PostRepository
 */
abstract class AbstractBaseRepository extends EntityRepository
{
    const LIST_DEFAULT_LIMIT = 5;

    public function latest($criteria = [], $limit = null, $offset = 0)
    {
        if (is_null($limit)) {
            $limit = static::LIST_DEFAULT_LIMIT;
        }

        return $this->findBy($criteria, ['createdAt' => 'DESC'], $limit, $offset);
    }

    public function save(AbstractBaseEntity $entity)
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

}
