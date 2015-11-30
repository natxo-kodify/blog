<?php

namespace Kodify\BlogBundle\Model\Handler\Exception;


class CommandHandlerException extends \Exception
{
    public $message = 'An exception occurs while handling command';
}