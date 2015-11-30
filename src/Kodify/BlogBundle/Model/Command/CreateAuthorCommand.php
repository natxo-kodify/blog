<?php

namespace Kodify\BlogBundle\Model\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAuthorCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @param string $name
     * @return CreateAuthorCommand
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}