<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentsControllerTest extends BaseFunctionalTest
{
    public function setUp()
    {
        parent::setUp();
        $this->client->insulate();
        $this->createAuthors(1);
        $this->createPosts(2, 1);
    }

    public function testPostNoComments()
    {
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextFound($crawler, "There are no comments");
    }

    /**
     * @dataProvider countDataProvider
     */
    public function testPostWithComments($commentsToCreate)
    {
        $this->createComments($commentsToCreate, 1);
        $this->createComments($commentsToCreate, 2);
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextNotFound(
            $crawler,
            "There are no comments",
            'Empty list found, it should have comments'
        );

        $this->assertSame(
            $commentsToCreate,
            substr_count($crawler->html(), 'Comment by: Author'),
            "We should find $commentsToCreate messages from the author"
        );
        for ($i = 0; $i < $commentsToCreate; ++$i) {
            $this->assertTextFound($crawler, "Comment content$i");
        }
    }

    protected function createAuthors($count)
    {
        for ($i = 0; $i < $count; ++$i) {
            $author = new Author();
            $author->setName("Author$i");
            $this->entityManager()->persist($author);
        }
        $this->entityManager()->flush();
    }

    protected function createPosts($count, $author)
    {
        for ($i = 0; $i < $count; ++$i) {
            $post = new Post();
            $post->setTitle('Title' . $i);
            $post->setContent('Content' . $i);
            $post->setAuthor($this->entityManager()->getReference('KodifyBlogBundle:Author', $author));
            $this->entityManager()->persist($post);
        }
        $this->entityManager()->flush();
    }

    protected function createComments($count, $post)
    {
        for ($i = 0; $i < $count; ++$i) {
            $comment = new Comment();
            $comment->setContent("Comment content$i");
            $comment->setPost($this->entityManager()->getReference('KodifyBlogBundle:Post', $post));
            $comment->setAuthor($this->entityManager()->getReference('KodifyBlogBundle:Author', 1));
            $this->entityManager()->persist($comment);
        }
        $this->entityManager()->flush();
    }

    public function countDataProvider()
    {
        return [
            ['count' => rand(1, 5)]
        ];
    }
}
