<?php

namespace Kodify\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Rating
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\RatingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Rating extends AbstractBaseEntity
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
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    protected $author;
    
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="postId", referencedColumnName="id")
     */
    protected $post;
    
    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

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
     * Set Rating
     *
     * @param int $rating
     * @return Rating
     */
    public function setRating($rating)
    {
    	$this->rating = $rating;
    
    	return $this;
    }
    
    /**
     * Get Rating
     *
     * @return int
     */
    public function getRating()
    {
    	return $this->rating;
    }
    
    /**
     * Set author
     *
     * @param \Kodify\BlogBundle\Entity\Author $author
     * @return Author
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
     * @return Post
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
    
}
