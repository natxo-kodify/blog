<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Tests\Fixtures\AuthorsFixture;
use Kodify\BlogBundle\Tests\BaseFunctionalTestCase;

class AuthorsControllerTest extends BaseFunctionalTestCase
{
    public function testIndexNoAuthors()
    {
        $crawler = $this->client->request('GET', '/authors');
        $this->assertTextFound($crawler, "There are no authors, let's create some!!");
    }

    public function testIndexWithAuthors()
    {
        $this->loadFixtures(new AuthorsFixture());

        $crawler = $this->client->request('GET', '/authors');
        $this->assertTextNotFound($crawler, "There are no authors, let's create some!!");
        $this->assertTextFound($crawler, AuthorsFixture::SOMEONE);
        $this->assertTextFound($crawler, AuthorsFixture::OVER);
        $this->assertTextFound($crawler, AuthorsFixture::RAINBOW);
    }

    public function testCreateAuthorGetRequest()
    {
        $crawler = $this->client->request('GET', '/authors/create');
        $this->assertTextNotFound($crawler, "Author Created!");
        $this->assertTextFound($crawler, 'Name');
    }

    public function testCreateAuthorPostRequestNoData()
    {
        $crawler = $this->client->request('POST', '/authors/create');
        $this->assertTextNotFound($crawler, "Author Created!");
        $this->assertTextFound($crawler, 'Name');
    }
}
