<?php

namespace Kodify\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="comments")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="postId", referencedColumnName="id")
     */
    protected $post;


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
     * Set content
     *
     * @param string $content
     * @return Comment
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
}
