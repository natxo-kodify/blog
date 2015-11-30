<?php
/**
 * Created by PhpStorm.
 * User: adrianr
 * Date: 30/11/15
 * Time: 20:04
 */

namespace Kodify\BlogBundle\Model\Command;

use Kodify\BlogBundle\Entity\Author;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePostCommand
{
    /**
     * @var Author
     * @Assert\Type("\Kodify\BlogBundle\Entity\Author")
     */
    private $author;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $title;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return CreatePostCommand
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CreatePostCommand
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return CreatePostCommand
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

}