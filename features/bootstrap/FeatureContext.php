<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Post;

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

    public function __construct(EntityManager $entity_manager)
    {
        $this->em = $entity_manager;
        $this->author_repository = $this->em->getRepository('Kodify\BlogBundle\Entity\Author');
        $this->post_repository = $this->em->getRepository('Kodify\BlogBundle\Entity\Post');
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
            $author = $this->author_repository->findBy(['name' => $row['author']]);
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
            $author = $this->author_repository->findBy(['name' => $row['author']]);
            $post = $this->post_repository->findBy(['title' => $row['post title']]);
            $comment = new Comment($row['text'], $post, $author);
            $this->em->persist($comment);
        }
    }

    /**
     * @Given I visit the page for the post with title :arg1
     */
    public function iVisitThePageForThePostWithTitle($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see a message saying there are no comments
     */
    public function iShouldSeeAMessageSayingThereAreNoComments()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see a comments section with :arg1 comment
     */
    public function iShouldSeeACommentsSectionWithComment($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then The comment I see says :arg1
     */
    public function theCommentISeeSays($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I don't see the comment :arg1
     */
    public function iDonTSeeTheComment($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I click on the button :arg1
     */
    public function iClickOnTheButton($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I fill the form with :arg1
     */
    public function iFillTheFormWith($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then a comment should be created for the post with the provided data
     */
    public function aCommentShouldBeCreatedForThePostWithTheProvidedData()
    {
        throw new PendingException();
    }
}
