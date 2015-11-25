<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Comment;
use PHPUnit_Framework_Assert as Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $author_repository;
    protected $post_repository;
    protected $comment_repository;
    protected $post;
    protected $comment_form_data;

    public function __construct(EntityManager $entity_manager)
    {
        $this->em = $entity_manager;
        $this->author_repository = $this->em->getRepository('Kodify\BlogBundle\Entity\Author');
        $this->post_repository = $this->em->getRepository('Kodify\BlogBundle\Entity\Post');
        $this->comment_repository = $this->em->getRepository('Kodify\BlogBundle\Entity\Comment');
    }

    /**
     * @Given the following authors exist:
     */
    public function theFollowingAuthorsExist(TableNode $table)
    {
        foreach ($table as $row) {
            $author = new Author($row['name']);
            $this->em->persist($author);
        }
    }

    /**
     * @Given the following posts exist:
     */
    public function theFollowingPostsExist(TableNode $table)
    {
        foreach ($table as $row) {
            $author = $this->author_repository->findOneBy(['name' => $row['author']]);
            $post = new Post($row['title'], $row['content'], $author);
            $this->em->persist($post);
        }
    }

    /**
     * @Given the following comments exist:
     */
    public function theFollowingCommentsExist(TableNode $table)
    {
        foreach ($table as $row) {
            $post = $this->post_repository->findOneBy(['title' => $row['post title']]);
            $author = $this->author_repository->findOneBy(['name' => $row['author']]);
            $comment = new Comment($row['text'], $post, $author);
            $this->em->persist($comment);
        }
    }

    /**
     * @Given I visit the page for the post with title :title
     */
    public function iVisitThePageForThePostWithTitle($title)
    {
        $this->post = $this->post_repository->findOneBy(['title' => $title]);
        Assert::assertInstanceOf('Kodify\BlogBundle\Entity\Post', $this->post);
    }

    /**
     * @Then I should see a message saying there are no comments
     */
    public function iShouldSeeAMessageSayingThereAreNoComments()
    {
        Assert::assertEquals($this->post->getComments()->count(), 0);
    }

    /**
     * @Then I should see a comments section with :num_comments comment
     */
    public function iShouldSeeACommentsSectionWithComment($num_comments)
    {
        Assert::assertEquals($this->post->getComments()->count(), $num_comments);
    }

    /**
     * @Then The comment I see says :text
     */
    public function theCommentISeeSays($text)
    {
        $comment = $this->post->getComments()->first;
        Assert::assertEquals($comment->getText(), $text);
    }

    /**
     * @Then I don't see the comment :text
     */
    public function iDonTSeeTheComment($text)
    {
        $comment = $this->comment_repository->findOneBy(['text' => $text]);
        Assert::assertFalse($this->post->getComments()->contains($comment));
    }

    /**
     * @When I click on the button :button_caption
     */
    public function iClickOnTheButton($button_caption)
    {
        switch ($button_caption) {
            case 'create comment':
                // @TODO: Make sure controller exists?
                break;
            case 'publish':
                $author = $this->author_repository->findOneBy(['name' => $this->comment_form_data->author]);
                $comment = new Comment($this->comment_form_data->text, $this->post, $author);
                $this->em->persist($comment);
                break;
        }
    }

    /**
     * @When I fill the form with :comment_data
     */
    public function iFillTheFormWith($comment_data)
    {
        $this->comment_form_data = json_decode($comment_data);
    }

    /**
     * @Then a comment should be created for the post with the provided data
     */
    public function aCommentShouldBeCreatedForThePostWithTheProvidedData()
    {
        $comment = array_shift($this->comment_repository->findBy([], ['createdAt' => 'DESC'], 1));
        Assert::assertInstanceOf('Kodify\BlogBundle\Entity\Comment', $comment);
        Assert::assertEquals($comment->getText(), $this->comment_form_data->text);
        Assert::assertEquals($comment->getAuthor()->name, $this->comment_form_data->author);
    }
}
