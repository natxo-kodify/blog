<?php

namespace Kodify\BlogBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

use Kodify\BlogBundle\Entity\Post;
use PHPUnit_Framework_Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;

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
        $em = $this->getContainer()->get("doctrine")->getManager();
        $author_names = array();
        foreach ($table->getHash() as $author) {
            $author_names[] = $author["name"];
        }
        $current_authors = $em->getRepository("KodifyBlogBundle:Author")->findBy(array("name" => $author_names));

        PHPUnit_Framework_Assert::assertEquals(count($current_authors), count($author_names));
    }

    /**
     * @Given the following posts exist:
     */
    public function theFollowingPostsExist(TableNode $table)
    {
        $em = $this->getContainer()->get("doctrine")->getManager();
        foreach ($table->getHash() as $post) {
            echo "Checking " . $post["title"];
            $post = $em->getRepository("KodifyBlogBundle:Post")
                ->searchWithAuthor($post["title"], $post["content"], $post["author"]);

            PHPUnit_Framework_Assert::assertNotEquals(null, $post);
        }

    }

    /**
     * @Given I visit the page for the post with title :arg1
     */
    public function iVisitThePageForThePostWithTitle($arg1)
    {
        $this->visit($arg1);
    }

    /**
     * @Then I should see a message saying there are no ratings
     */
    public function iShouldSeeAMessageSayingThereAreNoRatings()
    {
        $this->assertPageMatchesText("/there are no ratings/");
    }

    /**
     * @When I give the post a rating of :arg1
     */
    public function iGiveThePostARatingOf($arg1)
    {
        $this->clickLink("star" . $arg1);
    }

    /**
     * @Then I should see that the post has a rating of :arg1
     */
    public function iShouldSeeThatThePostHasARatingOf($arg1)
    {
        $stars_checked = 0;
        for ($i = Post::MINRATE; $i < Post::MAXRATE; $i++) {
            if ($this->assertElementContains("star" . $i, '<i class="fa fa-star"></i>')) {
                $stars_checked++;
            }
        }
        PHPUnit_Framework_Assert::assertEquals($arg1, $stars_checked);
    }

    /**
     * @Given I visit the posts list page
     */
    public function iVisitThePostsListPage()
    {
        $router = $this->getContainer()->get("router");
        $this->visit($router->generateUrl("home"));
    }

    /**
     * @Given Post with title :arg1 has a mean rating of :arg2
     */
    public function postWithTitleHasAMeanRatingOf($arg1, $arg2)
    {
        $em = $this->getContainer()->get("doctrine")->getManager();
        $post = $em->getRepository("KodifyBlogBundle:Post")->findOneBy(array("title" => $arg1));
        $post->setRate($arg2);
        $post->setRateClicks(1);
        $post->setRateTotal($arg2);
        $em->flush();

        PHPUnit_Framework_Assert::assertEquals($post->getRate(), $arg2);
    }

    /**
     * @Then Posts should be ordered by date
     */
    public function postsShouldBeOrderedByDate()
    {
        $session = $this->getSession();
        $page = $session->getPage();
        var_dump($page->find('css', ".panel-heading >"));

    }

    /**
     * @Then I choose :arg1
     */
    public function iChoose($arg1)
    {
        $this->clickLink($arg1);
    }

    /**
     * @Then Post with title :arg1 is before post with title :arg2
     */
    public function postWithTitleIsBeforePostWithTitle($arg1, $arg2)
    {

    }

    /**
     * @Given I visit the home page
     */
    public function iVisitTheHomePage()
    {
        $this->visit("/");
        $this->assertHomepage();
    }

    /**
     * @Then The post with title :arg1 is on first column, first row
     */
    public function thePostWithTitleIsOnFirstColumnFirstRow($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then The post with title :arg1 is on the second column, first row
     */
    public function thePostWithTitleIsOnTheSecondColumnFirstRow($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then The post with title :arg1 is on the first column, second row
     */
    public function thePostWithTitleIsOnTheFirstColumnSecondRow($arg1)
    {
        throw new PendingException();
    }

}
