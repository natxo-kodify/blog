<?php
namespace Kodify\BlogBundle\Domain;

/**
 * CommentRepositoryInterface
 *
 */
interface CommentRepositoryInterface extends BaseRepositoryInterface
{
    
    /**
     * Gets the latest comments created for a given post
     *
     * @param PostInterface $post Post the comment belongs to
     * @param int $limit Number of comments to retrieve
     * @param int $offset Number of ordered comments to skip
     * @return array
     */
    public function getLatestByPost($post, $limit, $offset = 0);
}
