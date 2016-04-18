<?php
namespace Kodify\BlogBundle\Domain;

/**
 * PostRepositoryInterface
 *
 */
interface PostRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * Perform a find operation in the data layer to get the first result that match the search criteria.
     *
     * @param array $criteria An array of key-value criteria the object must match
     * @param array|null $orderBy Order by an specific field before getting the first one
     *
     * @return object|null The domain object instance or NULL if it cannot be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null);
    
    /**
     * Gets the latest authors created
     *
     * @param int $limit Number of authors to retrieve
     * @param int $offset Number of ordered authors to skip
     * @return array
     */
    public function latest($limit, $offset = 0);
}
