<?php
namespace Kodify\BlogBundle\Domain;

/**
 * CommentInterface
 *
 * Interface representing what a Comment domain object shall do (independently of the database)
 */
interface CommentInterface extends BaseDomainInterface
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set text
     *
     * @param string $text
     * @return CommentInterface
     */
    public function setText($text);

    /**
     * Get text
     *
     * @return string
     */
    public function getText();

    /**
     * Set post
     *
     * @param PostInterface $post
     * @return CommentInterface
     */
    public function setPost($post);

    /**
     * Get post
     *
     * @return PostInterface
     */
    public function getPost();

    /**
     * Set author
     *
     * @param AuthorInterface $author
     * @return CommentInterface
     */
    public function setAuthor($author);

    /**
     * Get author
     *
     * @return AuthorInterface
     */
    public function getAuthor();
}
