<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentsControllerTest extends BaseFunctionalTest
{
    public function testCreateCommentGetRequest()
    {
        $this->createPost();

        $crawler = $this->client->request('GET', '/post/1/comment');
        $this->assertTextNotFound($crawler, "Comment Created!");
        $this->assertTextFound($crawler, 'Author');
        $this->assertTextFound($crawler, 'Text');
    }

    public function testCreateCommentPostRequestNoData()
    {
        $this->createPost();

        $crawler = $this->client->request('POST', '/post/1/comment');
        $this->assertTextNotFound($crawler, "Comment Created!");
        $this->assertTextFound($crawler, 'Author');
        $this->assertTextFound($crawler, 'Text');
    }

    protected function createPost()
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
    }

}
