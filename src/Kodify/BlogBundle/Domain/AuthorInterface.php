<?php
namespace Kodify\BlogBundle\Domain;

/**
 * AuthorInterface
 *
 * Interface representing what an Author domain object shall do (independently of the database)
 */
interface AuthorInterface extends DomainBaseInterface
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     * @return AuthorInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Add videos
     * TODO:: Determine why there are post instances
     *
     * @param PostInterface $videos
     * @return AuthorInterface
     */
    public function addVideo($videos);

    /**
     * Remove videos
     * TODO:: Determine why there are post instances
     *
     * @param PostInterface $videos
     */
    public function removeVideo($videos);

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos();
}
