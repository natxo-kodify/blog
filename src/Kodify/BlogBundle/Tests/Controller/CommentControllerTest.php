<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentControllerTest extends BaseFunctionalTest
{
    public function testCreateCommentGetRequest()
    {
        $this->createPosts(1);
        $crawler = $this->client->request('GET', '/comments/1/create');
        $this->assertTextNotFound(
            $crawler,
            "Comment Created!"
        );
        $this->assertEquals(
            $crawler->filter('label[for="comment_text"]')->text(),
            'Text'
        );
        $this->assertEquals(
            $crawler->filter('label[for="comment_author"]')->text(),
            'Author'
        );
    }

    public function testCreateCommentPostRequestNoData()
    {
        $this->createPosts(1);
        $crawler = $this->client->request('POST', '/comments/1/create');
        $this->assertTextNotFound(
            $crawler,
            "Comment Created!"
        );
    }

    public function testSendForm()
    {
        $this->createPosts(1);
        $crawler = $this->client->request('GET', '/comments/1/create');

        $button = $crawler->selectButton('comment[save]');
        $form = $button->form([
            'comment[text]'   => 'Testing comment',
            'comment[author]' => 1,
        ]);

        $crawler = $this->client->submit($form);
        $this->assertTextFound(
            $crawler,
            "Comment Created!"
        );
    }
}
