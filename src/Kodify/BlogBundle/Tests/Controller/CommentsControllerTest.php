<?php

namespace Kodify\BlogBundle\Tests\Controller;

use Kodify\BlogBundle\Tests\Fixtures\AuthorsFixture;
use Kodify\BlogBundle\Tests\Fixtures\CommentsFixture;
use Kodify\BlogBundle\Tests\Fixtures\PostsFixture;
use Kodify\BlogBundle\Tests\BaseFunctionalTestCase;

/**
 * Feature: Comments
 *   As a Blog manager
 *   I want to allow comments from authors
 *   In order that they interact a little bit
 */
class CommentsControllerTest extends BaseFunctionalTestCase
{

    public function setUp()
    {
        parent::setUp();

        //Until now it's always needed to load the fixtures
        $this->loadFixtures(new CommentsFixture());
    }

    //Scenario: No comments on a post page
    public function testNoCommentsOnAPostPage()
    {
        //Given I visit the page for the post with title "once"
        $crawler = $this->client->request('GET', sprintf('/posts/%s', PostsFixture::ONCE_ID));

        //Then I should see a message saying there are no comments
        $this->assertTextFound($crawler, "There are no comments");
    }

    //Scenario: See comments on a post page
    public function testSeeCommentsOnAPostPage()
    {
        //Given I visit the page for the post with title "way"
        $crawler = $this->client->request('GET', sprintf('/posts/%s', PostsFixture::WAY_ID));

        //Then I should see a comments section with 1 comment
        $this->assertElementWithClassFound($crawler, 'comments', 1);
        $this->assertElementWithClassFound($crawler, 'comment', 1);

        //And The comment I see says "nice!!"
        $this->assertTextFound($crawler, CommentsFixture::NICE);

        //And I don't see the comment "Is that a song?"
        $this->assertTextNotFound($crawler, CommentsFixture::SONG);
    }

    //Scenario: Create a comment
    public function testCreateComment()
    {
        //Given I visit the page for the post with title "once"
        $crawler = $this->client->request('GET', sprintf('/posts/%s', PostsFixture::ONCE_ID));

        //When I click on the button "create comment"
        $linkCreateComment = $crawler->selectLink('Create Comment')->link();
        $crawler = $this->client->click($linkCreateComment);

        //And I fill the form with "{'text':'Judy Garland was great!', 'author':'someone'}"
        $commentText = 'Judy Garland was great!';
        $this->assertTextFound($crawler, 'Text');
        $this->assertTextFound($crawler, 'Author');
        $formData = [
            'comment[text]' => $commentText,
            'comment[author]' => AuthorsFixture::SOMEONE_ID
        ];

        //And I click on the button "publish"
        $this->assertTextFound($crawler, 'Publish');
        $form = $crawler->selectButton('Publish')->form($formData);
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        //Then a comment should be created for the post with the provided data
        //I don't think it's a good idea to test the persistence itself in the Acceptance tests...

        // Then I should see a comments section with 1 comment
        $this->assertElementWithClassFound($crawler, 'comments', 1);
        $this->assertElementWithClassFound($crawler, 'comment', 1);

        //And The comment I see says "Judy Garland was great!"
        $this->assertTextFound($crawler, $commentText);
    }
}
