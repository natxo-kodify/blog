<?php

namespace Kodify\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment extends AbstractBaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="text", type="text")
     *
     */
    private $text;


    /**
     *
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="comments")
     */
    protected $author;


    /**
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     */
    protected $post;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Post
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * Set author
     *
     * @param \Kodify\BlogBundle\Entity\Author $author
     * @return Comment
     */
    public function setAuthor(\Kodify\BlogBundle\Entity\Author $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Kodify\BlogBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }


    /**
     * Set post
     *
     * @param \Kodify\BlogBundle\Entity\Post $post
     * @return Comment
     */
    public function setPost(\Kodify\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Kodify\BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Comment
     */
    public function setUpdatedAt($updatedAt = NULL)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @ORM\PreUpdate
     */
    public function markAsUpdated()
    {
        // Add your code here
    }

    /**
     * @ORM\PrePersist
     */
    public function initializeCreatedAt()
    {
        // Add your code here
    }
}
