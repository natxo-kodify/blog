<?php

namespace Kodify\BlogBundle\Tests\Controller;


use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Tests\BaseFunctionalTest;

class CommentsControllerTest extends BaseFunctionalTest
{
    
	public function testPostHasNoComments()
    {
        $crawler = $this->client->request('GET', '/posts/3');
        $this->assertTextFound($crawler, "there are no comments");
    }

	public function testPostHasExpectedComment()
    {
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextFound($crawler, "nice!");
    }

	public function testPostDoesNotHaveExpectedComment()
    {
        $crawler = $this->client->request('GET', '/posts/1');
        $this->assertTextNotFound($crawler, "Is that a song?");
    }
	
	public function testCreatePostComment()
    {
    	$postId = 1;
    	$string = 'Judy Garland was great!';
       	$this->createComment($postId, $string);
	    
		$crawler = $this->client->request('GET', '/posts/'.$postId);
        $this->assertTextFound($crawler, $string );
		
    }
	
	protected function createComment($postId,$text)
    {
        $author = $this->entityManager()->getRepository('KodifyBlogBundle:Author')->findOneBy( array('name'=>'someone') );
		$post = $this->entityManager()->getRepository('KodifyBlogBundle:Post')->find( $postId );
        
        $comment = new Comment();
        $comment->setText($text);
        $comment->setAuthor($author);
		$comment->setPost($post);
        $this->entityManager()->persist($comment);
        
        $this->entityManager()->flush();
    }
	
}
