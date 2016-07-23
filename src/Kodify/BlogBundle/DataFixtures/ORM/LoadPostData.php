<?php


namespace Kodify\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kodify\BlogBundle\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->createPost($manager, 'way', 'up high', 'someone');
        $this->createPost($manager, 'land', 'I heard of', 'over');
        $this->createPost($manager, 'once', 'In a lullaby', 'rainbow');


        $manager->flush();
    }

    private function createPost(ObjectManager $manager, $title, $content, $author)
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($this->getReference($author));
        $manager->persist($post);
        $this->addReference($title, $post);
    }

    public function getOrder()
    {
        return 2;
    }
}