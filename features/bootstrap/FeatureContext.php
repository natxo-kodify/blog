<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Kodify\BlogBundle\Entity\Comment;
use PHPUnit_Framework_Assert as PHPUnit;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{

    use \Behat\Symfony2Extension\Context\KernelDictionary;

    /**
     * @BeforeScenario
     */
    public function resetDatabase()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $purger->purge();
    }

    /**
     * @Given the following authors exist:
     */
    public function theFollowingAuthorsExist(TableNode $authors)
    {
        $this->loadAuthors($authors);
    }

    /**
     * @Given the following posts exist:
     */
    public function theFollowingPostsExist(TableNode $posts)
    {

        $this->loadPost($posts);
    }

    /**
     * @Given the following comments exist:
     */
    public function theFollowingCommentsExist(TableNode $comments)
    {
        $this->loadComments($comments);
    }

    /**
     * @Given the following posts ratings exist:
     */
    public function theFollowingPostsRatingsExist(TableNode $ratings)
    {
        $this->loadRatings($ratings);
    }

    /**
     * @Given Post with title :title has a mean rating of :rating
     */
    public function postWithTitleHasAMeanRatingOf($title, $rating)
    {
        $postRepository = $this->getContainer()->get('kodify.repository.post');
        /** @var \Kodify\BlogBundle\Entity\Post $post */
        $post = $postRepository->findOneBy(['title' => $title]);

        if(!$post) {
            throw new \InvalidArgumentException(sprintf('Not matching post for title %s', $title));
        }

        PHPUnit::assertEquals($post->getCurrentRating(), $rating);
    }

    /**
     * @Then Posts should be ordered by date
     */
    public function postsShouldBeOrderedByDate()
    {
        $postRepository = $this->getContainer()->get('kodify.repository.post');
        $posts = $postRepository->latest();

        /** @var \Kodify\BlogBundle\Entity\Post|null $current */
        $current = null;
        /** @var \Kodify\BlogBundle\Entity\Post $post */
        foreach ($posts as $post) {
            if($current === null) {
                $current = $post;
                continue;
            }

            if($current->getCreatedAt() >= $post->getCreatedAt()){
                return false;
            }
        }

        return true;
    }

    /**
     * @Then the post with title :title should have a comment with the text :text
     */
    public function thePostWithTitleShouldHaveACommentWithTheText($title, $text)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $commentRepository = $em->getRepository('KodifyBlogBundle:Comment');
        $comment = $commentRepository->findOneBy(['text' => $text]);
        $postRepository = $em->getRepository('KodifyBlogBundle:Post');
        $post = $postRepository->findOneBy(['title' => $title]);
        PHPUnit::assertTrue($post->getComments()->contains($comment));
    }

    /**
     * @When I give the post a rating of :rating
     */
    public function iGiveThePostARatingOf($rating)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find('css', '[name=rating][value=' . $rating . ']');
        $element->isChecked();
    }

    /**
     * @param TableNode $authors
     */
    private function loadAuthors(TableNode $authors)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach ($authors as $row) {
            $author = new \Kodify\BlogBundle\Entity\Author();
            $author->setName($row['name']);
            $em->persist($author);
        }
        $em->flush();
    }

    /**
     * @param TableNode $posts
     */
    private function loadPost(TableNode $posts)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $authorRepository = $container->get('kodify.repository.author');

        foreach ($posts as $row) {
            $author = $authorRepository->findOneBy(['name' => $row['author']]);
            $post = new  \Kodify\BlogBundle\Entity\Post();
            $post->setAuthor($author);
            $post->setContent($row['content']);
            $post->setTitle($row['title']);
            $em->persist($post);
        }
        $em->flush();
    }

    /**
     * @param TableNode $comments
     */
    private function loadComments(TableNode $comments)
    {
        $container = $this->getContainer();
        $postRepository = $container->get('kodify.repository.post');
        $authorRepository = $container->get('kodify.repository.author');
        $em = $container->get('doctrine')->getManager();

        foreach ($comments as $row) {
            $post = $postRepository->findOneBy(['title' => $row['post title']]);
            $author = $authorRepository->findOneBy(['name' => $row['author']]);
            $comment = new Comment();
            $comment->setAuthor($author);
            $comment->setPost($post);
            $comment->setText($row['text']);
            $em->persist($comment);
        }
        $em->flush();
    }

    /**
     * @param TableNode $ratings
     */
    private function loadRatings(TableNode $ratings)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        foreach ($ratings as $row) {
            $postRepository = $container->get('kodify.repository.post');
            $post = $postRepository->findOneBy(['title' => $row['post_title']]);
            if (!$post) {
                throw new \InvalidArgumentException();
            }
            $rating = new \Kodify\BlogBundle\Entity\PostRating();
            $rating->setPost($post);
            $rating->setValue($row['rating']);
            $em->persist($rating);
        }
        $em->flush();
    }

}
