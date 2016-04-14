<?php

namespace Kodify\BlogBundle\Test\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\Exception;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
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
        DoctrinePurger::purge($this->getService('doctrine.orm.entity_manager')->getConnection());
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
        $authorRepo = $this->getRepository(Author::class);
        foreach ($table->getHash() as $authorData) {
            $author = new Author();
            $author->setName($authorData['name']);

            $authorRepo->save($author);
        }
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
    }

    /**
     * @Given I visit the page for the post with title ":argument"
     */
    public function iVisitThePageForThePostWithTitle($arg1)
    {
        $postRepository = $this->getRepository(Post::class);
        $post = $postRepository->findOneByTitle($arg1);

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
     * @Given The comment I see says ":argument"
     */
    public function theCommentISeeSays($arg1)
    {
        $this->assertElementContainsText('div#comments div.panel.panel-info div.panel-body', $arg1);
    }

    /**
     * @Given I don't see the comment ":argument"
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
        $formData = json_decode($formDataJson);
        foreach ($formData as $field => $value) {
            $this->fillField($field, $value);
            $this->providedData[$field] = $value;
        }
    }

    /**
     * @When I click on the button ":argument"
     */
    public function iClickOnTheButton($arg1)
    {
        $this->pressButton($arg1);
    }

    /**
     * @Then a comment should be created for the post with the provided data
     */
    public function aCommentShouldBeCreatedForThePostWithTheProvidedData()
    {
        $commentRepo = $this->getRepository(Comment::class);
        $comment = $commentRepo->findOneByPostId($this->providedData['postId']);

        if ($comment->getAuthor()->getName() != $this->providedData['author']
         || $comment->getText() != $this->providedData['text']) {
            throw new \Exception('Comment has not been created with provided data.');
        }

        $this->providedData = [];
    }

}
