<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentsControllerTest extends WebTestCase
{
	public function testCommentsViewPost()
	{
		$text = 'Testing comments!';
		$this->createComments(3, $text);
		$crawler = $this->client->request('GET', '/posts/1');
		$this->assertTextFound($crawler, $text . '0');
		$this->assertTextFound($crawler, $text . '1');
		$this->assertTextNotFound($crawler, $text . '2');
	}
	
	protected function createComments($count, $text)
	{
		$author = new Author();
        $author->setName('Author');
        $this->entityManager()->persist($author);
        $this->entityManager()->flush();
        $post = new Post();
        $post->setTitle('Title');
        $post->setContent('Content');
        $post->setAuthor($author);
        $this->entityManager()->persist($post);
        $this->entityManager()->flush();       
		for ($i = 0; $i < $count; ++$i) {
			$comment = new Comment();
			$comment->setPost($post);
			$comment->setAuthor($author);
			$comment->setText($text . $i);
			$this->entityManager()->persist($comment);
		}
		$this->entityManager()->flush();
	}
}
