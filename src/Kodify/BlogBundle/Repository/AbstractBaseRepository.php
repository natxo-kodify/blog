<?php

namespace Kodify\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractBaseRepository extends EntityRepository
{
    const LIST_DEFAULT_LIMIT = 5;

    public function latest($limit = null, $offset = 0)
    {
        if (is_null($limit)) {
            $limit = static::LIST_DEFAULT_LIMIT;
        }

        return $this->findBy([], ['createdAt' => 'DESC'], $limit, $offset);
    }

    /**
     * @param mixed $entity
     */
    public function save($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }
}
