<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the following authors exist:
     */
    public function theFollowingAuthorsExist(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given the following posts exist:
     */
    public function theFollowingPostsExist(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given the following comments exist:
     */
    public function theFollowingCommentsExist(TableNode $table)
    {
        throw new PendingException();
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

    /**
     * @Then I should see a message saying there are no ratings
     */
    public function iShouldSeeAMessageSayingThereAreNoRatings()
    {
        throw new PendingException();
    }

    /**
     * @When I give the post a rating of :arg1
     */
    public function iGiveThePostARatingOf($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see that the post has a rating of :arg1
     */
    public function iShouldSeeThatThePostHasARatingOf($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I visit the posts list page
     */
    public function iVisitThePostsListPage()
    {
        throw new PendingException();
    }

    /**
     * @Given Post with title :arg1 has a mean rating of :arg2
     */
    public function postWithTitleHasAMeanRatingOf($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then Posts should be ordered by date
     */
    public function postsShouldBeOrderedByDate()
    {
        throw new PendingException();
    }

    /**
     * @Then I choose :arg1
     */
    public function iChoose($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then Post with title :arg1 is before post with title :arg2
     */
    public function postWithTitleIsBeforePostWithTitle($arg1, $arg2)
    {
        throw new PendingException();
    }
}
