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
     * @Given the following posts ratings exist:
     */
    public function theFollowingPostsRatingsExist(TableNode $ratings)
    {
        $this->loadRatings($ratings);
    }

    /**
     * @When I give the post a rating of :rating
     */
    public function iGiveThePostARatingOf($rating)
    {
        $session = $this->getSession();
        $dom = $session->getPage();
        $ratingButtonNode = $dom->findAll('css', '.rating-value');
        foreach ($ratingButtonNode as $node) {
            if ((integer)$node->getText() === (integer)$rating) {
                $node->click();
            }
        }
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
     * @Given Post with title :title has a mean rating of :rating
     */
    public function postWithTitleHasAMeanRatingOf($title, $rating)
    {
        $session = $this->getSession();
        $dom = $session->getPage();
        $postPanelNodes = $dom->findAll('css', '.post-panel');

        /** @var \Behat\Mink\Element\NodeElement $title */
        foreach ($postPanelNodes as $panelNode) {
            $titleNode = $panelNode->find('css', '.post-title');
            if($titleNode->getText() != $title){
                continue;
            }
            $ratingNode = $panelNode->find('css', '.post-rating');
            PHPUnit::assertEquals($ratingNode->getText(), $rating);
        }
        return false;
    }

    /**
     * @Then Posts should be ordered by date
     */
    public function postsShouldBeOrderedByDate()
    {
        $session = $this->getSession();
        $dom = $session->getPage();
        $postPanelNodes = $dom->findAll('css', '.post-panel');

        $previousPostDate = null;
        /** @var \Behat\Mink\Element\NodeElement $title */
        foreach ($postPanelNodes as $panelNode) {
            $dateNode = $panelNode->find('css', '.post-date');
            if($previousPostDate === null) {
                $previousPostDate = new \DateTime($dateNode->getText());
                continue;
            }
            $currentPostDate = new \DateTime($dateNode->getText());
            if($previousPostDate < $currentPostDate){
                throw new \Exception('Incorrect order posts order');
            }
        }
    }

    /**
     * @Then Post with title :afterTitle is after post with title :beforeTitle
     */
    public function postWithTitleIsAfterPostWithTitle($afterTitle, $beforeTitle)
    {
        $session = $this->getSession();
        $dom = $session->getPage();
        $postPanelNodes = $dom->findAll('css', '.post-panel');

        $isBeforeFind = false;
        /** @var \Behat\Mink\Element\NodeElement $panelNode */
        foreach ($postPanelNodes as $panelNode) {
            $titleNode = $panelNode->find('css', '.post-title');
            if($titleNode->getText() == $beforeTitle){
                $isBeforeFind = true;
                continue;
            }
            if(!$isBeforeFind && $titleNode->getText() == $afterTitle) {
                throw new \Exception('Incorrect order posts order');
            }
        }
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
            /** @var \Kodify\BlogBundle\Entity\Post $post */
            $post = $postRepository->findOneBy(['title' => $row['post_title']]);
            if (!$post) {
                throw new \InvalidArgumentException();
            }
            $rating = new \Kodify\BlogBundle\Entity\PostRating();
            $rating->setValue($row['rating']);
            $post->addRating($rating);
            $em->persist($post);
        }
        $em->flush();
    }

}
