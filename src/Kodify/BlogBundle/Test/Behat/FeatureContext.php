<?php

namespace Kodify\BlogBundle\Test\Behat;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
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
