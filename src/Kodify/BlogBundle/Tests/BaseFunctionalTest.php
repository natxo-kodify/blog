<?php

namespace Kodify\BlogBundle\Tests;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseFunctionalTest extends WebTestCase
{
    protected $entityManager;
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->cleanDb();
    }

    public function tearDown()
    {
        $this->cleanDb();
    }

    protected function cleanDb()
    {
        $this->clearTableByName('Author');
        $this->clearTableByName('Post');
        $this->clearTableByName('Comment');
    }

    protected function entityManager()
    {
        if ($this->entityManager == null) {
            $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        }

        return $this->entityManager;
    }

    protected function clearTableByName($tableName)
    {
        $connection = $this->entityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($tableName);
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }

    protected function assertTextFound($crawler, $text, $times = 1, $message = '')
    {
        if ($message == '') {
            $message = "{$text} did not appear {$times} times";
        }
        $this->assertSame(
            $times,
            $crawler->filter('html:contains("' . $text . '")')->count(),
            $message
        );
    }

    protected function assertTextNotFound($crawler, $text, $message = null)
    {
        if (is_null($message)) {
            $message = "{$text} Should not appear on the page";
        }

        return $this->assertTextFound($crawler, $text, 0, $message);
    }

    protected function createPosts($count)
    {
        $author = new Author();
        $author->setName('Author');
        $this->entityManager()->persist($author);
        $this->entityManager()->flush();
        $posts = [];
        for ($i = 0; $i < $count; ++$i) {
            $post = new Post();
            $post->setTitle('Title' . $i);
            $post->setContent('Content' . $i);
            $post->setAuthor($author);
            $this->entityManager()->persist($post);
            $posts[] = $post;
        }
        $this->entityManager()->flush();

        return $posts;
    }

    protected function addCommentToPost($post, $count)
    {
        $author = new Author();
        $author->setName('Author');
        $this->entityManager()->persist($author);
        $this->entityManager()->flush();
        for ($i = 0; $i < $count; ++$i) {
            $comment = new Comment();
            $comment->setText('Comment ' . $i);
            $comment->setPost($post);
            $comment->setAuthor($author);
            $this->entityManager()->persist($comment);
            $this->entityManager()->refresh($post);
        }
        $this->entityManager()->flush();
    }


}
