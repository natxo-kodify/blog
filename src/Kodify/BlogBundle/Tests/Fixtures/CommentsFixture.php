<?php

namespace Kodify\BlogBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

//TODO:: use Kodify\BlogBundle\Entity\Comment;

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
        //TODO:: Load fixtures here
    }
}
