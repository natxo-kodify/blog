<?php

namespace Kodify\BlogBundle\Tests\Service;

use Kodify\BlogBundle\Domain\PostInterface;
use Kodify\BlogBundle\Service\PostService;
use PHPUnit_Framework_MockObject_MockObject;

use Symfony\Component\Form\FormFactoryInterface;
use Kodify\BlogBundle\Domain\CommentInterface;
use Kodify\BlogBundle\Domain\CommentRepositoryInterface;
use Kodify\BlogBundle\Service\CommentService;


/**
 * Class NetworkServiceTest
 * 
 */
class CommentServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommentService
     */
    private $service;

    /**
     * @var CommentRepositoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var FormFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $formFactoryMock;

    /**
     * @var PostService|PHPUnit_Framework_MockObject_MockObject
     */
    private $postServiceMock;

    /**
     * @var CommentInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $domainMock;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        parent::setUp();

        $this->repositoryMock = $this->getMock(CommentRepositoryInterface::class);
        $this->domainMock = $this->getMock(CommentInterface::class);
        $this->formFactoryMock = $this->getMock(FormFactoryInterface::class);
        $this->postServiceMock = $this->getMockBuilder(PostService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->service = new CommentService(
            $this->repositoryMock,
            $this->formFactoryMock,
            $this->postServiceMock
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
            $this->formFactoryMock,
            $this->postServiceMock
        );
    }

    public function testGetLatestByPostGetsPostAndDelegatesToRepository()
    {
        $validPostId = 1;
        $wrongPostId = 2;
        $validPost = $this->getMock(PostInterface::class);

        $this->postServiceMock
            ->expects($this->exactly(2))
            ->method('findById')
            ->will($this->returnValueMap([
                [$validPostId, $validPost],
                [$wrongPostId, null],
            ]));

        $this->repositoryMock
            ->expects($this->once())
            ->method('getLatestByPost')
            ->will($this->returnValueMap([
                [$validPost, 1, 0, $this->domainMock]
            ]));

        $this->assertSame($this->domainMock, $this->service->getLatestByPost($validPostId, 1),
            'CommentService is not calling correctly the expected collaborators to get the latest Comments for a given post.'
        );

        $this->assertEmpty($this->service->getLatestByPost($wrongPostId, 3, 1),
            'CommentService is not calling correctly the expected collaborators to get the latest Comments for a given post.'
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
