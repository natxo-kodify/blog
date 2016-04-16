<?php

namespace Kodify\BlogBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Kodify\BlogBundle\Entity\Post;

class PostsFixture extends AbstractFixture implements DependentFixtureInterface
{
    const Way = 'way';
    const Land = 'land';
    const Once = 'once';

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            'Kodify\BlogBundle\Tests\Fixtures\AuthorsFixture'
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entities = [
            [
                'title' => self::Way,
                'content' => 'up high',
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::Someone))
            ],[
                'title' => self::Land,
                'content' => 'I heard of',
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::Over))
            ],[
                'title' => self::Once,
                'content' => 'In a lullaby',
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::Rainbow))
            ]
        ];
        
        foreach ($entities as $data) {
            $entity = new Post();
            $entity->setTitle($data['title']);
            $entity->setContent($data['content']);
            $entity->setAuthor($data['author']);
            $manager->persist($entity);
            $this->setReference(sprintf('post:%s', $entity->getTitle()), $entity);
        }
        $manager->flush();
    }
}
