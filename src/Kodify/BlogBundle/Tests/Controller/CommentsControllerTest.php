<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentsControllerTest extends BaseFunctionalTest
{

    protected function createComments($count)
    {
        $author = new Author();
        $author->setName('Author');
        $this->entityManager()->persist($author);
        $this->entityManager()->flush();

        $post = new Post();
        $post->setTitle('Title');
        $post->setContent('Content');
        $post->setAuthor($author);
        $this->entityManager()->persist($post);
        $this->entityManager()->flush();

        for ($i = 0; $i < $count; ++$i)
        {
            $comment = new Comment();
            $comment->setContent('Comment content ' . $i);
            $comment->setAuthor($author);
            $comment->setPost($post);
            $comment->setCreatedAt( new \DateTime() );
            $comment->setUpdatedAt( new \DateTime() );
            $this->entityManager()->persist($comment);
        }
        $this->entityManager()->flush();
    }
    
}
