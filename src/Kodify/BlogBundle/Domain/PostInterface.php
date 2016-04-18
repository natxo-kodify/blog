<?php
namespace Kodify\BlogBundle\Domain;

/**
 * PostInterface
 *
 * Interface representing what a Post domain object shall do (independently of the database)
 */
interface PostInterface extends DomainBaseInterface
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set title
     *
     * @param string $title
     * @return PostInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set content
     *
     * @param string $content
     * @return PostInterface
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Set author
     *
     * @param AuthorInterface $author
     * @return PostInterface
     */
    public function setAuthor($author);

    /**
     * Get author
     *
     * @return AuthorInterface
     */
    public function getAuthor();
}
