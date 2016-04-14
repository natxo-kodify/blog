<?php

namespace Kodify\BlogBundle\Test\Behat;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class FeatureContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @param string $serviceName
     * @return mixed
     */
    protected function getService($serviceName)
    {
        return $this->getContainer()->get($serviceName);
    }

    /**
     * @param string $className
     * @return mixed
     */
    protected function getRepository($className)
    {
        $em = $this->getService('doctrine.orm.entity_manager');

        return $em->getRepository($className);
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
     * @Given I visit the page for the post with title ":argument"
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
     * @Then I should see a comments section with :number comment
     */
    public function iShouldSeeACommentsSectionWithComment($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given The comment I see says ":argument"
     */
    public function theCommentISeeSays($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I don't see the comment ":argument"
     */
    public function iDonTSeeTheComment($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given I fill the form with ":argument"
     */
    public function iFillTheFormWith($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I click on the button ":argument"
     */
    public function iClickOnTheButton($arg1)
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
