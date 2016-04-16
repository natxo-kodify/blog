<?php

namespace Kodify\BlogBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Kodify\BlogBundle\Entity\Author;

class AuthorsFixture extends AbstractFixture
{
    const SOMEONE = 'someone';
    const SOMEONE_ID = 1;
    const OVER = 'over';
    const OVER_ID = 2;
    const RAINBOW = 'rainbow';
    const RAINBOW_ID = 3;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entities = [
            ['name' => self::SOMEONE],
            ['name' => self::OVER],
            ['name' => self::RAINBOW],
        ];
        
        foreach ($entities as $data) {
            $entity = new Author();
            $entity->setName($data['name']);
            $manager->persist($entity);
            $this->setReference(sprintf('author:%s', $entity->getName()), $entity);
        }
        $manager->flush();
    }
}
