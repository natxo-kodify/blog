<?php

namespace Kodify\BlogBundle\Tests\Service;

use PHPUnit_Framework_MockObject_MockObject;

use Symfony\Component\Form\FormFactoryInterface;
use Kodify\BlogBundle\Domain\AuthorInterface;
use Kodify\BlogBundle\Domain\AuthorRepositoryInterface;
use Kodify\BlogBundle\Service\AuthorService;


/**
 * Class AuthorServiceTest
 *
 */
class AuthorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorService
     */
    private $service;

    /**
     * @var AuthorRepositoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var FormFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $formFactoryMock;

    /**
     * @var AuthorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $domainMock;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->getMock(AuthorRepositoryInterface::class);
        $this->domainMock = $this->getMock(AuthorInterface::class);
        $this->formFactoryMock = $this->getMock(FormFactoryInterface::class);

        $this->service = new AuthorService(
            $this->repositoryMock,
            $this->formFactoryMock
        );

    }

    /**
     * teardown any static object changes and restore them.
     */
    public function tearDown()
    {
        parent::tearDown();

        unset(
            $this->service,
            $this->repositoryMock,
            $this->domainMock,
            $this->formFactoryMock
        );
    }

    public function testGetLatestDelegatesToRepository()
    {
        $this->repositoryMock
            ->expects($this->exactly(2))
            ->method('latest')
            ->will($this->returnValueMap([
                [1, 5, $this->domainMock],
                [0, 0, null],
            ]));

        $this->assertSame($this->domainMock, $this->service->getLatest(1, 5),
            'AuthorService is not calling correctly the expected collaborators to get the latest Authors.'
        );

        $this->assertNull($this->service->getLatest(0),
            'AuthorService is not calling correctly the expected collaborators to get the latest Authors.'
        );
    }

    public function testPersistDelegatesToRepository()
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->domainMock);

        $this->service->persist($this->domainMock);
    }

    public function testCreateFormDelegatesToFormFactory()
    {
        $this->markTestSkipped('The function for the formFactory requires creating a new instance 
            and I don\'t know how to avoid it.');
    }
}
