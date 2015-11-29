<?php

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

}
