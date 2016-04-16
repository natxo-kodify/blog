<?php

namespace Kodify\BlogBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Kodify\BlogBundle\Entity\Post;

class PostsFixture extends AbstractFixture implements DependentFixtureInterface
{
    const WAY = 'way';
    const WAY_ID = 1;
    const LAND = 'land';
    const LAND_ID = 2;
    const ONCE = 'once';
    const ONCE_ID = 3;

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
                'title' => self::WAY,
                'content' => 'up high',
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::SOMEONE))
            ],[
                'title' => self::LAND,
                'content' => 'I heard of',
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::OVER))
            ],[
                'title' => self::ONCE,
                'content' => 'In a lullaby',
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::RAINBOW))
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
