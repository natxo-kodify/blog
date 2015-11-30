<?php

namespace Kodify\BlogBundle\Model\Handler;


class CreateAuthorHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CreateAuthorHandler
     */
    private $handler;

    public function setUp()
    {
        $authorRepositoryMock = $this->getMockBuilder('Kodify\BlogBundle\Repository\AuthorRepository')->disableOriginalConstructor()->getMock();

        $this->handler = new CreateAuthorHandler($authorRepositoryMock);
    }

    public function testCreateAnAuthor()
    {
        $createAuthorCommandMock = $this->getMockBuilder('Kodify\BlogBundle\Model\Command\CreateAuthorCommand')->getMock();
        $createAuthorCommandMock->expects($this->any())->method('getName')->willReturn('TestAuthor');

        $this->handler->handle($createAuthorCommandMock);
    }

}