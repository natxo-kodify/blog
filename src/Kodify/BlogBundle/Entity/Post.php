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
     * Max rate value
     */
    const MAXRATE = 5;

    /**
     * Min rate value
     */
    const MINRATE = 0;

    /**
     * @var integer
     * @Assert\GreaterThanOrEqual(value = 0)
     * @Assert\LessThanOrEqual(value = 5)
     * @Assert\NotBlank()
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate = 0;

    /**
     * @var integer
     * @Assert\GreaterThanOrEqual(value = 0)
     * @Assert\NotBlank()
     * @ORM\Column(name="rate_clicks", type="integer")
     */
    private $rate_clicks = 0;

    /**
     * @var integer
     * @Assert\GreaterThanOrEqual(value = 0)
     * @Assert\NotBlank()
     * @ORM\Column(name="rate_total", type="integer")
     */
    private $rate_total = 0;


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
     * Set rate
     *
     * @param integer $rate
     * @return Post
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set rate_clicks
     *
     * @param integer $rateClicks
     * @return Post
     */
    public function setRateClicks($rateClicks)
    {
        $this->rate_clicks = $rateClicks;

        return $this;
    }

    /**
     * Get rate_clicks
     *
     * @return integer
     */
    public function getRateClicks()
    {
        return $this->rate_clicks;
    }


    /**
     * Set rate_total
     *
     * @param integer $rateTotal
     * @return Post
     */
    public function setRateTotal($rateTotal)
    {
        $this->rate_total = $rateTotal;

        return $this;
    }

    /**
     * Get rate_total
     *
     * @return integer
     */
    public function getRateTotal()
    {
        return $this->rate_total;
    }

}
