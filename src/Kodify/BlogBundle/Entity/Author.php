<?php

namespace Kodify\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Author
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\AuthorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Author extends AbstractBaseEntity
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
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="author", cascade={"persist"})
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="author")
     */
    protected $comments;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return Author
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add post
     *
     * @param \Kodify\BlogBundle\Entity\Post $post
     * @return Author
     */
    public function addPost(\Kodify\BlogBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Kodify\BlogBundle\Entity\Post $post
     */
    public function removePost(\Kodify\BlogBundle\Entity\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPost()
    {
        return $this->posts;
    }


    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * Add comments
     *
     * @param \Kodify\BlogBundle\Entity\Comment $comments
     * @return Author
     */
    public function addComment(\Kodify\BlogBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Kodify\BlogBundle\Entity\Comment $comments
     */
    public function removeComment(\Kodify\BlogBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }
    
}
