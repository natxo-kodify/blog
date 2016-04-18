<?php

namespace Kodify\BlogBundle\Tests\Service;

use PHPUnit_Framework_MockObject_MockObject;

use Symfony\Component\Form\FormFactoryInterface;
use Kodify\BlogBundle\Domain\PostInterface;
use Kodify\BlogBundle\Domain\PostRepositoryInterface;
use Kodify\BlogBundle\Service\PostService;

/**
 * Class NetworkServiceTest
 *
 * @package App\Test\TestCase\Model\Service
 */
class PostServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PostService
     */
    private $service;

    /**
     * @var PostRepositoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var FormFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $formFactoryMock;

    /**
     * @var PostInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $domainMock;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->getMock(PostRepositoryInterface::class);
        $this->domainMock = $this->getMock(PostInterface::class);
        $this->formFactoryMock = $this->getMock(FormFactoryInterface::class);

        $this->service = new PostService(
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
            $this->domainMock
        );
    }

    public function testGetLatestDelegatesToRepository()
    {
        $this->repositoryMock
            ->expects($this->exactly(2))
            ->method('latest')
            ->will($this->returnValueMap([
                [1, 0, $this->domainMock],
                [0, 0, null],
            ]));

        $this->assertSame($this->domainMock, $this->service->getLatest(1),
            'PostService is not calling correctly the expected collaborators to get the latest Posts.'
        );

        $this->assertNull($this->service->getLatest(0),
            'PostService is not calling correctly the expected collaborators to get the latest Posts.'
        );
    }

    public function testFindByIdDelegatesToRepository()
    {
        $validId = 1;
        $wrongId = 2;

        $this->repositoryMock
            ->expects($this->exactly(2))
            ->method('findOneBy')
            ->will($this->returnValueMap([
                [['id' => $validId], null, $this->domainMock],
                [['id' => $wrongId], null, null],
            ]));

        $this->assertSame($this->domainMock, $this->service->findById($validId),
            'PostService is not calling correctly the expected collaborators to get the latest Posts.'
        );

        $this->assertNull($this->service->findById($wrongId),
            'PostService is not calling correctly the expected collaborators to get the latest Posts.'
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
