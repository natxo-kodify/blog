<?php

namespace Kodify\BlogBundle\Model\Handler;

class CreatePostHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateAPost()
    {
        $postRepositoryMock = $this->getMockBuilder('Kodify\BlogBundle\Repository\PostRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $handler = new CreatePostHandler($postRepositoryMock);

        $authorMock = $this->getMockBuilder('\Kodify\BlogBundle\Entity\Author')
            ->disableOriginalConstructor()
            ->getMock();

        $createPostCommand = $this->getMockBuilder('\Kodify\BlogBundle\Model\Command\CreatePostCommand')
            ->getMock();
        $createPostCommand->expects($this->once())->method('getAuthor')->willReturn($authorMock);
        $createPostCommand->expects($this->once())->method('getTitle')->willReturn('Test title');
        $createPostCommand->expects($this->once())->method('getContent')->willReturn('Test contents');

        $handler->handle($createPostCommand);
    }

}
