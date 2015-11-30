<?php

namespace Kodify\BlogBundle\Model\Handler;

use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Model\Command\CreateAuthorCommand;
use Kodify\BlogBundle\Model\Handler\Exception\CommandHandlerException;
use Kodify\BlogBundle\Repository\AuthorRepository;

class CreateAuthorHandler
{

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function handle(CreateAuthorCommand $command)
    {
        $author = new Author($command->getName());

        try {
            $this->authorRepository->save($author);
        } catch (\Exception $e) {
            throw new CommandHandlerException($e->getMessage(), 0, $e);
        }
    }

}