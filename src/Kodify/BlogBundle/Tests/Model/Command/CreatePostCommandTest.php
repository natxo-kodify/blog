<?php
/**
 * Created by PhpStorm.
 * User: adrianr
 * Date: 30/11/15
 * Time: 20:06
 */

namespace Kodify\BlogBundle\Model\Command;


class CreatePostCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testInstantiation()
    {
        $authorMock = $this->getMockBuilder('Kodify\BlogBundle\Entity\Author')
            ->disableOriginalConstructor()
            ->getMock();
        $createPostCommand = new CreatePostCommand();
        $createPostCommand->setAuthor($authorMock);
        $createPostCommand->setTitle('Test title');
        $createPostCommand->setContent('Test content');
    }

}
