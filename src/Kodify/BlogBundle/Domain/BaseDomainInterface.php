<?php
namespace Kodify\BlogBundle\Domain;

/**
 * BaseDomainInterface
 *
 * Common Interface shared by all domain objects
 */
interface BaseDomainInterface
{

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return mixed This object
     */
    public function setCreatedAt($createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return mixed This object
     */
    public function setUpdatedAt($updatedAt = null);

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * A string representation of this object
     *
     * @return string
     */
    public function __toString();
}
