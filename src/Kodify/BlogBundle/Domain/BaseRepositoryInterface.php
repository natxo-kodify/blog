<?php
namespace Kodify\BlogBundle\Domain;

/**
 * Interface BaseRepositoryInterface
 *
 * Interface representing the role of an object that accesses the data layer to obtain or persist domain objects.
 */
interface BaseRepositoryInterface
{

    /**
     * Save a domain object in the data layer
     *
     * @param mixed $domainObject Object to persist
     */
    public function persist($domainObject);
}
