<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentControllerTest extends BaseFunctionalTest
{

    public function testViewNoComments()
    {
        $this->createPosts(2);
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextFound($crawler, 'Title0');
        $this->assertTextFound($crawler, 'Content0');

        $this->assertTextNotFound($crawler, 'Title1');
        $this->assertTextNotFound($crawler, 'Content1');

        $this->assertTextFound($crawler, 'There are no comments');

    }

    public function testViewWithComments()
    {
        $this->createPosts(2,1);
        $crawler = $this->client->request('GET', '/posts/4');
        $this->assertTextFound($crawler, 'Title1');
        $this->assertTextFound($crawler, 'Content1');
        $this->assertTextFound($crawler, 'Text');
        

        $this->assertTextNotFound($crawler, 'Title0');
        $this->assertTextNotFound($crawler, 'Content0');

        $this->assertTextNotFound($crawler, 'There are no comments');

    }

    protected function createPosts($count, $createComments = 0)
    {
        $author = new Author();
        $author->setName('Author');
        $this->entityManager()->persist($author);
        $this->entityManager()->flush();
        for ($i = 0; $i < $count; ++$i) {
            $post = new Post();
            $post->setTitle('Title' . $i);
            $post->setContent('Content' . $i);
            $post->setAuthor($author);
            $this->entityManager()->persist($post);
        }
        $this->entityManager()->flush();

        if ($createComments == 1) $this->createComments(5,$post);
    }

    protected function createComments($count,$post)
    {
        for ($i = 0; $i < $count; ++$i) {
            $author = new Author();
            $author->setName('Author'.$i);
            $this->entityManager()->persist($author);
            $comment = new Comment();
            $comment->setText('Text'.$i);
            $comment->setPost($post);
            $comment->setAuthor($author);
            $this->entityManager()->persist($comment);
        }
        $this->entityManager()->flush();
    }
    
}
