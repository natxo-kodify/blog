<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Tests\Fixtures\PostsFixture;
use Kodify\BlogBundle\Tests\BaseFunctionalTestCase;

class PostsControllerTest extends BaseFunctionalTestCase
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
        $this->loadFixtures(new PostsFixture());

        $crawler = $this->client->request('GET', sprintf('/posts/%s', PostsFixture::WAY));
        $this->assertTextFound($crawler, PostsFixture::WAY);
        $this->assertTextNotFound($crawler, PostsFixture::LAND);
        $this->assertTextNotFound($crawler, PostsFixture::ONCE);
    }

    /**
     * Feature: Two columns for post list
     *   As a Blog manager
     *   I want to have a post list at two columns
     *   In order that it looks better
     */
    //Scenario: Visit home page
    public function testViewPostTwoColumns()
    {
        $this->loadFixtures(new PostsFixture());

        //Given I visit the home page
        $crawler = $this->client->request('GET', '/');

        //Then The post with title "way" is on first column, first row
        $domElement = $crawler->filter('.post.first-column')->getNode(0);
        $this->assertContains(PostsFixture::WAY,
            $domElement->textContent,
            'The post with title "way" is NOT on first column, first row'
        );

        //And  The post with title "land" is on the second column, first row
        $domElement = $crawler->filter('.post.second-column')->getNode(0);
        $this->assertContains(PostsFixture::LAND,
            $domElement->textContent,
            'The post with title "land" is NOT on the second column, first row'
        );

        //And  The post with title "once" is on the first column, second row
        $domElement = $crawler->filter('.post.first-column')->getNode(1);
        $this->assertContains(PostsFixture::ONCE,
            $domElement->textContent,
            'The post with title "once" is NOT on the first column, second row'
        );
    }

    /**
     * Independent from default fixtures.
     * Creates the given number of posts attached to a single author
     *
     * @param $count Number of posts to create
     */
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
