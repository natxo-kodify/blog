<?php

namespace Kodify\BlogBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Kodify\BlogBundle\Entity\Author;

class AuthorsFixture extends AbstractFixture
{
    const Someone = 'someone';
    const Over = 'over';
    const Rainbow = 'rainbow';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entities = [
            ['name' => self::Someone],
            ['name' => self::Over],
            ['name' => self::Rainbow],
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
