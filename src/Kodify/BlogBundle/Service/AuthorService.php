<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Form\Type\AuthorType;
use Symfony\Component\Form\FormFactoryInterface;
use Kodify\BlogBundle\Domain\AuthorInterface;
use Kodify\BlogBundle\Domain\AuthorRepositoryInterface;

use Kodify\BlogBundle\Entity\Author;


class AuthorService extends AppService
{

    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * AuthorService constructor.
     *
     * @param $authorRepository AuthorRepositoryInterface
     * @param $formFactory FormFactoryInterface
     */
    public function __construct($authorRepository, $formFactory)
    {
        $this->authorRepository = $authorRepository;
        $this->formFactory = $formFactory;
    }

    /**
     * Gets the latest authors
     *
     * @param $limit int The number of posts wanted
     * @param int $offset Number of ordered authors to skip
     * @return array The latest posts
     */
    public function getLatest($limit, $offset = 0) {
        return $this->authorRepository->latest($limit, $offset);
    }

    /**
     * Persists the given Author
     *
     * @param $author AuthorInterface
     */
    public function persist($author) {
        $this->authorRepository->persist($author);
    }

    /**
     * Creates a form related to an Author with the given options
     *
     * @param $authorType AuthorType A form AuthorType instance
     * @param $options array The given options to be passed to the form constructor
     * @return FormFactoryInterface
     */
    public function createForm($authorType, $options) {
        return $this->formFactory->create(
            $authorType,
            /**
             * TODO:: Remove the following dependency on a database entity
             * Don't know how to get around this using forms.
             * Also, creating an instance here makes it not properly untestable.
             */
            new Author(),
            $options);
    }
}
