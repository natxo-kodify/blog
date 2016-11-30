<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Tests\BaseFunctional;

class CommentControllerTest extends BaseFunctional
{
    public function testCreateCommentGetRequest()
    {
        $crawler = $this->client->request('GET', '/comment/create');
        $this->assertTextNotFound($crawler, "Comment Created!");
        $this->assertTextFound($crawler, 'Name');
    }

    public function testCreateCommentPostRequestWithCorrectValues()
    {
        $crawler = $this->client->request('POST', '/comment/create');
        $this->assertTextNotFound($crawler, "Comment Created!");
        $this->assertTextFound($crawler, 'Name');
    }
}
