<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Form\Type\AuthorType;
use Kodify\BlogBundle\Repository\AuthorRepository;
use Symfony\Component\Form\FormInterface;

class AuthorService extends AppService
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * AuthorService constructor.
     *
     * @param $authorRepository AuthorRepository
     */
    public function __construct($authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Gets the latest authors
     *
     * @param $limit int The number of posts wanted
     * @return array The latest posts
     */
    public function getLatest($limit) {
        return $this->authorRepository->latest($limit);
    }

    /**
     * Persists the given Author
     *
     * @param $author Author
     */
    public function persist($author) {
        $this->authorRepository->persist($author);
    }

    /**
     * Creates a form related to an Author with the given options
     *
     * @param $authorType AuthorType A form AuthorType instance
     * @param $options array The given options to be passed to the form constructor
     * @return FormInterface
     */
    public function createForm($authorType, $options) {
        return $this->container->get('form.factory')->create(
            $authorType,
            new Author(),
            $options);
    }
}
