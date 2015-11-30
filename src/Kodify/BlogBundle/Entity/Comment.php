<?php

namespace Kodify\BlogBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Kodify\BlogBundle\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment extends AbstractBaseEntity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $text;
    /**
     * @var Author
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Kodify\BlogBundle\Entity\Author", inversedBy="comments")
 *     @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;
    /**
     * @var Post
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Kodify\BlogBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Comment
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Post|null
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

}