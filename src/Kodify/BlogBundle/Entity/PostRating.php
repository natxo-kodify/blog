<?php

/**
 * Created by PhpStorm.
 * User: miquel
 * Date: 6/9/15
 * Time: 21:58.
 */
namespace Kodify\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostRating.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\PostRatingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PostRating
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var Post
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="ratings")
     * @ORM\JoinColumn(name="postId", referencedColumnName="id")
     */
    private $post;

    public function __construct(Post $post, $rating)
    {
        $this->post = $post;
        $this->rating = $rating;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }
}
