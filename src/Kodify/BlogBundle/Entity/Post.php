<?php

namespace Kodify\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"persist"})
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="post", cascade={"persist"})
     */
    protected $ratings;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
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
     * @param Author $author
     * @return Post
     */
    public function setAuthor(Author $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Comment $comment
     * @return Post
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * @param Comment $comment
     */
    public function removeVideo(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param Rating $rating
     * @return $this
     */
    public function addRating(Rating $rating)
    {
        $this->ratings[] = $rating;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        $numRatings = count($this->ratings);
        if ($numRatings == 0) {
            return null;
        }
        $total = 0;
        foreach($this->ratings as $rating) {
            $total += $rating->getValue();
        }

        return  $total / $numRatings;
    }


}
