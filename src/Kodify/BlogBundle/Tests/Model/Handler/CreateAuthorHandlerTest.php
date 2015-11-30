<?php

namespace Kodify\BlogBundle\Model\Handler;

use Doctrine\ORM\ORMInvalidArgumentException;


class CreateAuthorHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateAnAuthor()
    {
        $authorRepositoryMock = $this->getMockBuilder('Kodify\BlogBundle\Repository\AuthorRepository')->disableOriginalConstructor()->getMock();
        $handler = new CreateAuthorHandler($authorRepositoryMock);

        $createAuthorCommandMock = $this->getMockBuilder('Kodify\BlogBundle\Model\Command\CreateAuthorCommand')->getMock();
        $createAuthorCommandMock->expects($this->any())->method('getName')->willReturn('TestAuthor');

        $handler->handle($createAuthorCommandMock);
    }

}