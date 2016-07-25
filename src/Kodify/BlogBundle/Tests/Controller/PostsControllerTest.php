<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\DataFixtures\ORM\LoadAuthorData;
use Kodify\BlogBundle\DataFixtures\ORM\LoadCommentData;
use Kodify\BlogBundle\DataFixtures\ORM\LoadPostData;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class PostsControllerTest extends BaseFunctionalTest
{
    public function testIndexNoPosts()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTextFound($crawler, "There are no posts, let's create some!!");
    }

    /**
     * @dataProvider countDataProvider
     */
    public function testIndexWithPosts($postsToCreate, $countToCheck)
    {
        $this->createPosts($postsToCreate);
        $crawler = $this->client->request('GET', '/');
        $this->assertTextNotFound(
            $crawler,
            "There are no posts, let's create some!!",
            'Empty list found, it should have posts'
        );

        $this->assertSame(
            $countToCheck,
            substr_count($crawler->html(), 'by: Author'),
            "We should find $countToCheck messages from the author"
        );
        for ($i = 0; $i < $countToCheck; ++$i) {
            $this->assertTextFound($crawler, "Title{$i}");
            $this->assertTextFound($crawler, "Content{$i}");
        }
    }

    public function testViewNonExistingPost()
    {
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextFound($crawler, 'Post not found', 1);
    }

    public function testViewPost()
    {
        $this->createPosts(2);
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextFound($crawler, 'Title0');
        $this->assertTextFound($crawler, 'Content0');
        $this->assertTextNotFound($crawler, 'Title1');
        $this->assertTextNotFound($crawler, 'Content1');
    }

    public function testIndexPostsIn2Columns()
    {
        $this->addFixture(new LoadAuthorData());
        $this->addFixture(new LoadPostData());
        $this->executeFixtures();

        $crawler = $this->client->request('GET', '/');

        $this->assertTextNotFound(
            $crawler,
            "There are no posts, let's create some!!",
            'Empty list found, it should have posts'
        );

        $titleLink = $crawler->filter('div.col-md-6 > div.panel-heading > a')->eq(0);
        $this->assertSame('way', $titleLink->text());
        $titleLink = $crawler->filter('div.col-md-6 > div.panel-heading > a')->eq(1);
        $this->assertSame('land', $titleLink->text());
        $titleLink = $crawler->filter('div.col-md-6 > div.panel-heading > a')->eq(2);
        $this->assertSame('once', $titleLink->text());
    }

    public function testNoComments()
    {
        $this->addFixture(new LoadAuthorData());
        $this->addFixture(new LoadPostData());
        $this->addFixture(new LoadCommentData());
        $this->executeFixtures();

        $crawler = $this->client->request('GET', '/posts/3');

        $this->assertTextFound($crawler, "There are no comments, let's create one!!");
    }

    public function testWithComments()
    {
        $this->addFixture(new LoadAuthorData());
        $this->addFixture(new LoadPostData());
        $this->addFixture(new LoadCommentData());
        $this->executeFixtures();

        $crawler = $this->client->request('GET', '/posts/1');

        $this->assertTextFound($crawler, "nice!!");
        $this->assertTextNotFound($crawler, "Is that a song?");
    }

    protected function createPosts($count)
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
    }

    public function countDataProvider()
    {
        $rand = rand(1, 5);

        return [
            'lessThanLimit' => ['count' => $rand, 'expectedCount' => $rand],
            'moreThanLimit' => ['count' => rand(6, 9), 'expectedCount' => 5],
        ];
    }
}
