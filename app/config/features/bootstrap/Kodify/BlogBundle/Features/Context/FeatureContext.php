<?php

namespace Kodify\BlogBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

use Kodify\BlogBundle\Entity\Author;
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
        $this->clearTableByName("Authors");
        $em = $this->getContainer()->get("doctrine")->getManager();
        $author_names = 0;
        foreach ($table->getHash() as $author_params) {
            $author = $em->getRepository("KodifyBlogBundle:Author")->findBy(array("name" => $author_params["name"]));
            if (null === $author) {
                $author = new Author();
                $author->setName($author_params["name"]);
                $em->persist($author);
            }
            $author_names++;
        }
        $em->flush();

        PHPUnit_Framework_Assert::assertEquals(count($table->getHash()), $author_names);
    }

    /**
     * @Given the following posts exist:
     */
    public function theFollowingPostsExist(TableNode $table)
    {
        $this->cleanDb();
        $em = $this->getContainer()->get("doctrine")->getManager();
        foreach ($table->getHash() as $post_params) {
            $post = $em->getRepository("KodifyBlogBundle:Post")
                ->searchWithAuthor($post_params["title"], $post_params["content"], $post_params["author"]);
            if (null === $post) {
                $author = $em->getRepository("KodifyBlogBundle:Author")->findOneBy(array("name" => $post_params["author"]));
                if (null === $author) {
                    $author = new Author();
                    $author->setName($post_params["author"]);
                    $em->persist($author);
                    $em->flush();
                }
                $post = new Post();
                $post->setTitle($post_params["title"]);
                $post->setContent($post_params["content"]);
                $post->setAuthor($author);
                $em->persist($post);
                $em->flush();
            }
        }

    }

    /**
     * @Given I visit the page for the post with title :arg1
     */
    public function iVisitThePageForThePostWithTitle($arg1)
    {
        $em = $this->getContainer()->get("doctrine")->getManager();
        $post = $em->getRepository("KodifyBlogBundle:Post")->findOneBy(array("title" => $arg1));
        $this->visit("posts/" . $post->getId());
    }

    /**
     * @Then I should see a message saying there are no ratings
     */
    public function iShouldSeeAMessageSayingThereAreNoRatings()
    {
        $this->assertPageContainsText("there are no ratings");
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
        $session = $this->getSession();
        $page = $session->getPage();
        $stars_checked = $page->findAll("css", ".fa-star");

        PHPUnit_Framework_Assert::assertEquals($arg1, count($stars_checked));
    }

    /**
     * @Given I visit the posts list page
     */
    public function iVisitThePostsListPage()
    {
        $this->visit("/");
    }

    /**
     * @Given Post with title :arg1 has a mean rating of :arg2
     */
    public function postWithTitleHasAMeanRatingOf($arg1, $arg2)
    {
        $em = $this->getContainer()->get("doctrine")->getManager();

        $post = $em->getRepository("KodifyBlogBundle:Post")->findOneBy(array("title" => $arg1));
        if (null === $post) {
            $author = $em->getRepository("KodifyBlogBundle:Author")->findOneBy(array("name" => $arg1));
            if (null === $author) {
                $author = new Author();
                $author->setName($arg1);
                $em->persist($author);
                $em->flush();
            }

            $post = new Post();
            $post->setTitle($arg1);
            $post->setContent($arg1);
            $post->setAuthor($author);
            $em->persist($post);
            $em->flush();
        }
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
        $higher_position_date = new \DateTime();
        foreach ($page->findAll('css', ".date") as $date) {
            $current_position_date = new \DateTime($date->getText());
            PHPUnit_Framework_Assert::assertTrue($higher_position_date >= $current_position_date,
                "Current Post is newer than a Higher one");

            $higher_position_date = $current_position_date;
        }

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
        $session = $this->getSession();
        $page = $session->getPage();
        $titles = $page->findAll("css", ".panel-heading > a");
        $arg1_pos = 0;
        $arg2_pos = 0;
        foreach ($titles as $position => $title) {
            if ($title->getText() == $arg1) {
                $arg1_pos = $position;
            } elseif ($title->getText() == $arg2) {
                $arg2_pos = $position;
            }
        }
        PHPUnit_Framework_Assert::assertLessThan($arg2_pos, $arg1_pos);
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
        $this->checkPosition(0, 0, $arg1);
    }

    /**
     * @Then The post with title :arg1 is on the second column, first row
     */
    public function thePostWithTitleIsOnTheSecondColumnFirstRow($arg1)
    {
        $this->checkPosition(1, 0, $arg1);
    }

    /**
     * @Then The post with title :arg1 is on the first column, second row
     */
    public function thePostWithTitleIsOnTheFirstColumnSecondRow($arg1)
    {
        $this->checkPosition(0, 1, $arg1);
    }

    /**
     * Checks the text existence on the two-column's div table
     *
     * @param $column_position int of the column to check
     * @param $row_position int of the row to check
     * @param $argument string argument
     */
    private function checkPosition($column_position, $row_position, $argument)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $rows = $page->findAll("css", ".row.posts");
        $columns = $rows[$row_position]->findAll("css", ".panel-heading a");

        PHPUnit_Framework_Assert::assertEquals($columns[$column_position]->getText(), $argument);
    }

    protected function cleanDb()
    {
        $this->clearTableByName('Author');
        $this->clearTableByName('Post');
    }

    protected function clearTableByName($tableName)
    {
        $connection = $em = $this->getContainer()->get("doctrine")->getConnection();
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
}
