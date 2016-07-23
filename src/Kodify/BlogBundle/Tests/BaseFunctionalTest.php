<?php

namespace Kodify\BlogBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseFunctionalTest extends WebTestCase
{
    protected $entityManager;

    /** @var Client*/
    protected $client;

    /** @var ORMExecutor */
    private $fixtureExecutor;

    /** @var ContainerAwareLoader */
    private $fixtureLoader;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->cleanDb();
    }

    public function tearDown()
    {
        $this->cleanDb();
    }

    protected function cleanDb()
    {
        $this->clearTableByName('Author');
        $this->clearTableByName('Post');
    }

    protected function entityManager()
    {
        if ($this->entityManager == null) {
            $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        }

        return $this->entityManager;
    }

    protected function clearTableByName($tableName)
    {
        $connection = $this->entityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($tableName);
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }

    protected function assertTextFound($crawler, $text, $times = 1, $message = '')
    {
        if ($message == '') {
            $message = "{$text} did not appear {$times} times";
        }
        $this->assertSame(
            $times,
            $crawler->filter('html:contains("' . $text . '")')->count(),
            $message
        );
    }

    protected function assertTextNotFound($crawler, $text, $message = null)
    {
        if (is_null($message)) {
            $message = "{$text} Should not appear on the page";
        }

        return $this->assertTextFound($crawler, $text, 0, $message);
    }

    /**
     * Adds a new fixture to be loaded.
     *
     * @param FixtureInterface $fixture
     */
    protected function addFixture(FixtureInterface $fixture)
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }

    /**
     * Executes all the fixtures that have been loaded so far.
     */
    protected function executeFixtures()
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }

    /**
     * @return ORMExecutor
     */
    private function getFixtureExecutor()
    {
        if (!$this->fixtureExecutor) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }

        return $this->fixtureExecutor;
    }

    /**
     * @return ContainerAwareLoader
     */
    private function getFixtureLoader()
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
        }

        return $this->fixtureLoader;
    }
}
