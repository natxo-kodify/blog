<?php


namespace Kodify\BlogBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Kodify\BlogBundle\Entity\Author;

class LoadAuthorData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->createAuthor($manager, 'someone');
        $this->createAuthor($manager, 'over');
        $this->createAuthor($manager, 'rainbow');

        $manager->flush();
    }

    private function createAuthor(ObjectManager $manager, $name)
    {
        $author = new Author();
        $author->setName($name);
        $manager->persist($author);
        $this->addReference($name, $author);
    }

    public function getOrder()
    {
        return 1;
    }
}