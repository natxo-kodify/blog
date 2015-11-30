<?php

namespace Kodify\BlogBundle\Model\Command;


class CreateAuthorCommandTest extends \PHPUnit_Framework_TestCase
{

    private $createAuthorCommand;

    public function testInitialization()
    {
        $createAuthorCommand = new CreateAuthorCommand();
        $createAuthorCommand->setName('Author');
        $this->createAuthorCommand = $createAuthorCommand;
    }

}