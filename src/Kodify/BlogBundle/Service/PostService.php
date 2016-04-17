<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Repository\PostRepository;
use Symfony\Component\Form\FormInterface;


class PostService extends AppService
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * AuthorService constructor.
     *
     * @param $postRepository PostRepository
     */
    public function __construct($postRepository)
    {
        $this->postRepository = $postRepository;
    }
    
    /**
     * Gets the latest posts published
     *
     * @param $limit int The number of posts wanted
     * @return array The latest posts
     */
    public function getLatest($limit) {
        return $this->postRepository->latest($limit);
    }

    /**
     * Gets a Post object given its id
     * @param $id int The id of the post
     * @return Post
     */
    public function findById($id) {
        return $this->postRepository->find($id);
    }

    /**
     * Persists the given Post
     * @param $post Post
     */
    public function persist($post) {
        $this->postRepository->persist($post);
    }

    /**
     * Creates a form related to a Post with the given options
     *
     * @param $postType PostType A form AuthorType instance
     * @param $options array The given options to be passed to the form constructor
     * @return FormInterface
     */
    public function createForm($postType, $options) {
        return $this->container->get('form.factory')->create(
            $postType,
            new Post(),
            $options);
    }
}
