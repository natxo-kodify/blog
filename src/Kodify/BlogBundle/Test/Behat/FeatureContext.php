<?php

namespace Kodify\BlogBundle\Test\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Doctrine\ORM\EntityManagerInterface;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Rating;
use Kodify\BlogBundle\Test\DoctrinePurger;

class FeatureContext extends MinkContext
{
    use KernelDictionary;

    private $providedData = [];

    /**
     * @BeforeSuite
     */
    public static function prepareDB(BeforeSuiteScope $scope)
    {
        // rudimentary calls
        exec('app/console doctrine:database:drop --env=test --force');
        exec('app/console doctrine:database:create --env=test');
        exec('app/console doctrine:schema:create --env=test');
    }

    /**
     * @BeforeScenario
     * @database
     */
    public function purgeDB(BeforeScenarioScope $scope)
    {
        DoctrinePurger::purge($this->getEntityManager()->getConnection());
    }

    /**
     * @param string $serviceName
     * @return mixed
     */
    protected function getService($serviceName)
    {
        return $this->getContainer()->get($serviceName);
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    /**
     * @param string $className
     * @return mixed
     */
    protected function getRepository($className)
    {
        return $this->getEntityManager()->getRepository($className);
    }

    /**
     * Useful to avoid LOSING time trying to figure out what is wrong with the test... just the F***ING CACHE
     */
    protected function clearUnitOfWork()
    {
        $this->getEntityManager()->clear();
    }

    /**
     * @Given the following authors exist:
     */
    public function theFollowingAuthorsExist(TableNode $table)
    {
        $authorRepo = $this->getRepository(Author::class);
        foreach ($table->getHash() as $authorData) {
            $author = new Author();
            $author->setName($authorData['name']);

            $authorRepo->save($author);
        }
        $this->clearUnitOfWork();
    }

    /**
     * @Given the following posts exist:
     */
    public function theFollowingPostsExist(TableNode $table)
    {
        $postRepo = $this->getRepository(Post::class);
        $authorRepo = $this->getRepository(Author::class);
        foreach ($table->getHash() as $postData) {
            $author = $authorRepo->findOneByName($postData['author']);

            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setContent($postData['content']);
            $post->setAuthor($author);

            $postRepo->save($post);
        }
        $this->clearUnitOfWork();
    }

    /**
     * @Given the following comments exist:
     */
    public function theFollowingCommentsExist(TableNode $table)
    {
        $postRepo = $this->getRepository(Post::class);
        $authorRepo = $this->getRepository(Author::class);
        $commentRepo = $this->getRepository(Comment::class);
        foreach ($table->getHash() as $commentData) {
            $author = $authorRepo->findOneByName($commentData['author']);
            $post = $postRepo->findOneByTitle($commentData['post title']);

            $comment = new Comment();
            $comment->setText($commentData['text']);
            $comment->setPost($post);
            $comment->setAuthor($author);

            $commentRepo->save($comment);
        }
        $this->clearUnitOfWork();
    }

    /**
     * @Given the following ratings exist:
     */
    public function theFollowingRatingsExist(TableNode $table)
    {
        $postRepo = $this->getRepository(Post::class);
        $ratingRepo = $this->getRepository(Rating::class);
        foreach ($table->getHash() as $postData) {
            $post = $postRepo->findOneByTitle($postData['post title']);

            $rating = new Rating();
            $rating->setPost($post);
            $rating->setValue((int)$postData['rating']);

            $ratingRepo->save($rating);
        }
        $this->clearUnitOfWork();
    }

    /**
     * @Given I visit the page for the post with title ":argument"
     */
    public function iVisitThePageForThePostWithTitle($title)
    {
        $postRepository = $this->getRepository(Post::class);
        $post = $postRepository->findOneByTitle($title);

        // I don't like having to save state
        $this->providedData['postId'] = $post->getId();

        $this->visitPath('/posts/' . $post->getId());
    }

    /**
     * @Then I should see a message saying there are no comments
     */
    public function iShouldSeeAMessageSayingThereAreNoComments()
    {
        $this->assertPageContainsText('There are no comments for this post yet');
    }

    /**
     * @Then I should see a comments section with :number comment
     */
    public function iShouldSeeACommentsSectionWithComment($arg1)
    {
        $this->assertPageContainsText('There are ' . $arg1 . ' comments.');
        $this->assertElementOnPage('div#comments');
        $this->assertNumElements($arg1, 'div#comments div.panel.panel-info');
    }

    /**
     * @Then The comment I see says ":argument"
     */
    public function theCommentISeeSays($arg1)
    {
        $this->assertElementContainsText('div#comments div.panel.panel-info div.panel-body', $arg1);
    }

    /**
     * @Then I don't see the comment :argument
     */
    public function iDonTSeeTheComment($arg1)
    {
        $this->assertElementNotContainsText('div#comments div.panel.panel-info div.panel-body', $arg1);
    }

    /**
     * @Given I fill the form with ":json"
     */
    public function iFillTheFormWith($formDataJson)
    {
        $formData = json_decode(str_replace("'", '"', $formDataJson), true);

        foreach ($formData as $field => $value) {
            $this->setValueInFormField($field, $value);
            $this->providedData[$field] = $value;
        }
    }

    /**
     * @When I click on the button ":argument"
     */
    public function iClickOnTheButton($arg1)
    {
        $this->pressButton(ucfirst($arg1));
    }

    /**
     * @Then a comment should be created for the post with the provided data
     */
    public function aCommentShouldBeCreatedForThePostWithTheProvidedData()
    {
        $commentRepo = $this->getRepository(Comment::class);
        $comment = $commentRepo->findOneById($this->providedData['postId']);

        if ($comment->getAuthor()->getName() != $this->providedData['author']
            || $comment->getText() != $this->providedData['text']
        ) {
            throw new \Exception('Comment has not been created with provided data.');
        }

        $this->providedData = [];
    }

    /**
     * @Then I should see a message saying there are no ratings
     */
    public function iShouldSeeAMessageSayingThereAreNoRatings()
    {
        $this->assertPageContainsText('There are no ratings for this post.');
    }

    /**
     * @When I give the post a rating of ":rating"
     */
    public function iGiveThePostARatingOf($rating)
    {
        $this->setValueInFormField('value', $rating);
        $this->iClickOnTheButton('rate');
    }

    /**
     * @Then I should see that the post has a rating of ":rating"
     */
    public function iShouldSeeThatThePostHasARatingOf($rating)
    {
        $this->assertElementContainsText('#rating', $rating);
    }

    /**
     * @Given I visit the posts list page
     */
    public function iVisitThePostsListPage()
    {
        $this->visitPath('/posts');
    }

    /**
     * @Given Post with title ":title" has a mean rating of ":rating"
     */
    public function postWithTitleHasAMeanRatingOf($title, $rating)
    {
        $this->assertElementContains('span.rating[data-title=' . $title . ']', $rating);
    }

    /**
     * @Then Posts should be ordered by date
     */
    public function postsShouldBeOrderedByDate()
    {
        $this->assertElementOnPage('li[data-orderby="date"]');
    }

    /**
     * @Given I choose ":link"
     */
    public function iChoose($link)
    {
        $this->clickLink($link);
    }

    /**
     * @Then Post with title ":first" is before post with title ":second"
     */
    public function postWithTitleIsBeforePostWithTitle($first, $second)
    {
        $page = $this->getSession()->getPage();
        $items = $page->findAll('css', 'div.panel-heading a');
        foreach ($items as $k => $post) {
            $found[$post->getText()] = $k;
        }
        if ($found[$first] > $found[$second]) {
            throw new Exception(sprintf('Post with title "%s" was found before', $second));
        }
    }

    /**
     * @param string $field
     * @param mixed $value
     */
    private function setValueInFormField($field, $value)
    {
        $page = $this->getSession()->getPage();
        $fieldUppercase = ucfirst($field);

        $fieldElement = $page->findField($fieldUppercase);
        if ($fieldElement->getTagName() == 'select') {
            $page->selectFieldOption($fieldUppercase, $value);
        } else {
            $this->fillField($fieldUppercase, $value);
        }
    }

    /**
     * @Given I visit the home page
     */
    public function iVisitTheHomePage()
    {
        $this->visitPath('/');
    }

    /**
     * @Then The post with title ":title" is on the :col column, :row row
     */
    public function thePostWithTitleIsOnColumnRow($title, $col, $row)
    {
        $ordinalsToNumerals = [
            'first' => 1,
            'second' => 2,
            'third' => 3,
        ];
        $element = 'div.panel#r%d-c%d[data-title=%s]';
        $this->assertElementOnPage(sprintf($element, $ordinalsToNumerals[$row], $ordinalsToNumerals[$col], $title));
    }
}
