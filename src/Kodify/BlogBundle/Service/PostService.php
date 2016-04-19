<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Component\Form\FormFactoryInterface;
use Kodify\BlogBundle\Domain\PostRepositoryInterface;
use Kodify\BlogBundle\Domain\PostInterface;

use Kodify\BlogBundle\Entity\Post;


class PostService extends AppService
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * AuthorService constructor.
     *
     * @param $postRepository PostRepositoryInterface
     * @param $formFactory FormFactoryInterface
     */
    public function __construct($postRepository, $formFactory)
    {
        $this->postRepository = $postRepository;
        $this->formFactory = $formFactory;
    }
    
    /**
     * Gets the latest posts published
     *
     * @param $limit int The number of posts wanted
     * @param $offset int Number of ordered posts to skip
     * @return array The latest posts
     */
    public function getLatest($limit, $offset = 0) 
    {
        return $this->postRepository->latest($limit, $offset);
    }

    /**
     * Gets a Post object given its id
     * @param $id int The id of the post
     * @return PostInterface
     */
    public function findById($id) 
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }

    /**
     * Persists the given Post
     * @param $post PostInterface
     */
    public function persist($post) 
    {
        $this->postRepository->persist($post);
    }

    /**
     * Creates a form related to a Post with the given options
     *
     * @param $postType PostType A form AuthorType instance
     * @param $options array The given options to be passed to the form constructor
     * @return FormFactoryInterface
     */
    public function createForm($postType, $options) 
    {
        return $this->formFactory->create(
            $postType,
            /**
             * TODO:: Remove the following dependency on a database entity
             * Don't know how to get around this using forms.
             * Also, creating an instance here makes it not properly untestable.
             */
            new Post(),
            $options);
    }
}
