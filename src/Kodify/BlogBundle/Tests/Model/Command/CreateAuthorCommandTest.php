<?php

namespace Kodify\BlogBundle\Tests\Model\Command;


use Kodify\BlogBundle\Model\Command\CreateAuthorCommand;

class CreateAuthorCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testInitialization()
    {
        $createAuthorCommand = new CreateAuthorCommand();
        $createAuthorCommand->setName('Author');
        $this->createAuthorCommand = $createAuthorCommand;
    }

}