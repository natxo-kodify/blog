<?php
/**
 * Created by PhpStorm.
 * User: adrianr
 * Date: 30/11/15
 * Time: 17:55
 */

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