<?php

namespace Kodify\BlogBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\DBAL\Connection;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class BaseFunctionalTestCase extends WebTestCase
{
    protected $entityManager;
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Loader
     */
    protected $loader;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->loader = new Loader();
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

    /**
     * Loads the given fixtures
     *
     * @param $fixtures AbstractFixture|AbstractFixture[] Fixtures to be loaded
     */
    protected function loadFixtures($fixtures)
    {
        if (!is_array($fixtures)) {
            $fixtures = [$fixtures];
        }
        
        foreach ($fixtures as $fixture) {
            $this->loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager(), $purger);
        $executor->execute($this->loader->getFixtures());
    }

    /**
     * @return EntityManager
     */
    protected function entityManager()
    {
        if ($this->entityManager == null) {
            $this->entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
        }

        return $this->entityManager;
    }

    protected function clearTableByName($tableName)
    {
        /** @var Connection $connection */
        $connection = $this->entityManager()->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSQL($tableName);
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }

    /**
     * Find a text a concrete number of times in the given crawler
     *
     * @param Crawler $crawler Web crawler for a given page
     * @param string $text Text that must appear in there
     * @param int $times Number of times the text must appear
     * @param string $message Custom error message in case of failure
     */
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

        $this->assertTextFound($crawler, $text, 0, $message);
    }

    /**
     * @param Crawler $crawler Web crawler for a given page
     * @param string $class The class name
     * @param int $times Number of times the text must appear
     * @param string $message Custom error message in case of failure
     */
    protected function assertElementWithClassFound($crawler, $class, $times = 1, $message = null)
    {
        if (is_null($message)) {
            $message = "Elements with class {$class} did not appear {$times} times";
        }
        $this->assertSame(
            $times,
            $crawler->filter('.' . $class)->count(),
            $message
        );
    }
}
