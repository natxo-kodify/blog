<?php


namespace Kodify\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kodify\BlogBundle\Entity\Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->createComment($manager, 'nice!!', 'way', 'someone');
        $this->createComment($manager, 'Is that a song?', 'land', 'over');

        $manager->flush();
    }

    private function createComment(ObjectManager $manager, $text, $post, $author)
    {
        $comment = new Comment();
        $comment->setText($text);
        $comment->setPost($this->getReference($post));
        $comment->setAuthor($this->getReference($author));
        $manager->persist($comment);

    }

    public function getOrder()
    {
        return 3;
    }
}