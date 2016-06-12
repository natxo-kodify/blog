<?php

namespace Kodify\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post extends AbstractBaseEntity
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
     * @ORM\Column(name="title", type="text")
     * @Assert\NotBlank()
     *
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="posts")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    protected $author;
	
	/**
	 * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
	 */
    protected $comments; 
	
	/**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set author
     *
     * @param \Kodify\BlogBundle\Entity\Author $author
     * @return Post
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
     * Add Comment
     *
     * @param \Kodify\BlogBundle\Entity\Comment $comment
     * @return Post
     */
    public function addComment(\Kodify\BlogBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }
	
	/**
     * Remove comment
     *
     * @param \Kodify\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\Kodify\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }
	
	/**
     * Get Comments 
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
