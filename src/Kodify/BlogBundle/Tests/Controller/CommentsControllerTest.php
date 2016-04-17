<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Tests\Fixtures\CommentsFixture;
use Kodify\BlogBundle\Tests\Fixtures\PostsFixture;
use Kodify\BlogBundle\Tests\BaseFunctionalTestCase;

class CommentsControllerTest extends BaseFunctionalTestCase
{
    public function setUp()
    {
        parent::setUp();

        //Until now it's always needed to load the fixtures
        $this->loadFixtures(new CommentsFixture());
    }

    public function testNoCommentsOnAPostPage()
    {
        $crawler = $this->client->request('GET', sprintf('/posts/%s', PostsFixture::ONCE_ID));
        $this->assertTextFound($crawler, "There are no comments");
    }

    public function testSeeCommentsOnAPostPage()
    {
        $crawler = $this->client->request('GET', sprintf('/posts/%s', PostsFixture::WAY_ID));
        $this->assertElementWithClassFound($crawler, 'comments', 1);
        $this->assertElementWithClassFound($crawler, 'comment', 1);
        $this->assertTextFound($crawler, CommentsFixture::NICE);
        $this->assertTextNotFound($crawler, CommentsFixture::SONG);
    }
}
