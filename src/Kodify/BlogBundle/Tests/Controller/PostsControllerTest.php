<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Comment;
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
	
	public function testViewPostNoComments()
    {
        $this->createPosts(1);
        $crawler = $this->client->request('GET', '/posts/1');
        
		//Assert no comments found.
		$this->assertTextFound($crawler, 'No comments');
    }
	
	public function testViewPostWithComments() 
	{
		$this->createPosts(1,3); 
		$crawler = $this->client->request('GET', '/posts/1');
		
		$this->assertTextNotFound($crawler, 'No comments');
		
		$this->assertTextFound($crawler, 'Comment0');
		$this->assertTextFound($crawler, 'Comment1');
		$this->assertTextFound($crawler, 'Comment2');
	}
	
	public function testViewPostNoRatings() 
	{
		$this->createPosts(1); 
		$crawler = $this->client->request('GET', '/posts/1');
		
		$this->assertTextFound($crawler, 'No rating');		
	}
	
	public function testViewPostWithRatings() 
	{
		$this->createPosts(1);

		$post = $this->entityManager()->find('KodifyBlogBundle:Post',1);
		$post->addToRating(5);
		$post->addToRating(3);
		$this->entityManager()->persist($post);
		$this->entityManager()->flush();
		
		$crawler = $this->client->request('GET', '/posts/1');
		
		$this->assertTextNotFound($crawler, 'No rating');
		$this->assertTextFound($crawler, 'Rating AVG: 4');
	}
	
	public function testDefaultOrderHome() 
	{
		$this->createPosts(3);
		$crawler = $this->client->request('GET', '/');
		
		$this->assertEquals($crawler->filter('.panel-heading > a')->eq(0)->text(), 'Title0');
		$this->assertEquals($crawler->filter('.panel-heading > a')->eq(1)->text(), 'Title1');
		$this->assertEquals($crawler->filter('.panel-heading > a')->eq(2)->text(), 'Title2');
		
	}
	
	public function testRatingOrderHome() 
	{
		
		$this->createPosts(3);
		$post0 = $this->entityManager()->find('KodifyBlogBundle:Post',1);
		$post1 = $this->entityManager()->find('KodifyBlogBundle:Post',2);
		$post2 = $this->entityManager()->find('KodifyBlogBundle:Post',3);
		
		$post1->setAvgRating(2.7);
		$post2->setAvgRating(4);
		$post0->setAvgRating(5);
		
		$expectedTitles = ['Title0','Title2','Title1'];
		
		$crawler = $this->client->request('GET', '/home?order=rating');
		
		$this->assertEquals($crawler->filter('.panel-heading > a')->eq(0)->text(), $expectedTitles[0]);
		$this->assertEquals($crawler->filter('.panel-heading > a')->eq(1)->text(), $expectedTitles[1]);
		$this->assertEquals($crawler->filter('.panel-heading > a')->eq(2)->text(), $expectedTitles[2]);
		
	}

    protected function createPosts($count, $comments_count = 0)
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
			
			//in case we want to create comments too: 
			for ($j =0; $j < $comments_count; $j++) {
				$comment = new Comment();
				$comment->setAuthor($author);
				$comment->setPost($post);
				$comment->setContent('Comment' . $j);
				$this->entityManager()->persist($comment);
			}
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
