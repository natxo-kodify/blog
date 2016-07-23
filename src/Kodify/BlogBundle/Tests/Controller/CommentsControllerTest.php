<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\DataFixtures\ORM\LoadAuthorData;
use Kodify\BlogBundle\DataFixtures\ORM\LoadCommentData;
use Kodify\BlogBundle\DataFixtures\ORM\LoadPostData;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentsControllerTest extends BaseFunctionalTest
{
    public function testCreateComment()
    {
        $this->addFixture(new LoadAuthorData());
        $this->addFixture(new LoadPostData());
        $this->addFixture(new LoadCommentData());
        $this->executeFixtures();

        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/posts/3');

        $this->assertTextFound($crawler, "Create Comment");

        $link = $crawler->filter('a:contains("Create Comment")')->eq(0)->link();

        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('Publish')->form();

        $form['comment[text]'] = 'Judy Garland was great!';
        $form['comment[author]']->setValue(1);

        $crawler = $this->client->submit($form);

        $this->assertTextFound($crawler, "In a lullaby");
        $this->assertTextFound($crawler, "Judy Garland was great!");
        $this->assertTextFound($crawler, "someone");
    }
}
