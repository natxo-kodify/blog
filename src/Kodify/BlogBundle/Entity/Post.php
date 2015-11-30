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
    private $author;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Kodify\BlogBundle\Entity\Comment", mappedBy="post", cascade={"all"})
     */
    private $comments;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Kodify\BlogBundle\Entity\PostRating", mappedBy="post", cascade={"all"})
     */
    private $ratings;

    /**
     * @var integer
     * @ORM\Column(name="currentRating", type="integer", options={"default"=0})
     */
    private $currentRating = 0;

    public function __construct(Auhtor $author, $title, $content)
    {
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get author
     *
     * @return \Kodify\BlogBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment)
    {
        $comment->setPost($this);
        $this->comments->add($comment);

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param PostRating $rating
     * @return $this
     */
    public function addRating(PostRating $rating)
    {
        $rating->setPost($this);
        $this->ratings->add($rating);

        $this->updateRating();

        return $this;
    }

    /**
     * @param PostRating $rating
     * @return $this
     */
    public function removeRating(PostRating $rating)
    {
        $this->ratings->removeElement($rating);

        return $this;
    }

    /**
     * @return integer
     */
    public function rating()
    {
        return $this->currentRating;
    }

    /**
     * @return integer
     */
    private function updateRating()
    {
        $timesRated = $this->ratings->count();
        if ($timesRated === 0) {
            return 0;
        }

        $currentRating = 0;
        /** @var PostRating $rating */
        foreach ($this->ratings as $rating) {
            $currentRating += $rating->getValue();
        }

        $this->currentRating = floor($currentRating / $timesRated);
    }
}
