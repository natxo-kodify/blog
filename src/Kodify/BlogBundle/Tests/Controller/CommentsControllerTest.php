<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentsControllerTest extends BaseFunctionalTest
{
	public function testCreateCommentGetRequest()
    {
        $crawler = $this->client->request('GET', '/comments/create');
        $this->assertTextNotFound($crawler, "Comment Created!");
        $this->assertTextFound($crawler, 'Text');
    }

        public function testCreateCommentPostRequestNoData()
    {
        $crawler = $this->client->request('POST', '/comments/create');
        $this->assertTextNotFound($crawler, "Comment Created!");
        $this->assertTextFound($crawler, 'Text');
    }
    
}