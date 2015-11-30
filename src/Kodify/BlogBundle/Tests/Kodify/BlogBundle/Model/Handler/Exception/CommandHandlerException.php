<?php
/**
 * Created by PhpStorm.
 * User: adrianr
 * Date: 30/11/15
 * Time: 18:06
 */

namespace Kodify\BlogBundle\Model\Handler\Exception;


class CommandHandlerException extends \Exception
{
    public $message = 'An exception occurs while handling command';
}