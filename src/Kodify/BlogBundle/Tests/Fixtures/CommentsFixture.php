<?php

namespace Kodify\BlogBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Kodify\BlogBundle\Entity\Comment;

class CommentsFixture extends AbstractFixture implements DependentFixtureInterface
{
    const NICE = 'nice!!';
    const NICE_ID = 1;
    const SONG = 'Is that a song?';
    const SONG_ID = 2;

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            'Kodify\BlogBundle\Tests\Fixtures\PostsFixture'
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entities = [
            [
                'text' => self::NICE,
                'post' => $this->getReference(sprintf('post:%s', PostsFixture::WAY)),
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::SOMEONE))
            ],[
                'text' => self::SONG,
                'post' => $this->getReference(sprintf('post:%s', PostsFixture::LAND)),
                'author' => $this->getReference(sprintf('author:%s', AuthorsFixture::RAINBOW))
            ]
        ];

        foreach ($entities as $data) {
            $entity = new Comment();
            $entity->setText($data['text']);
            $entity->setPost($data['post']);
            $entity->setAuthor($data['author']);
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
