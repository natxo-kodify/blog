<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Component\Form\FormFactoryInterface;
use Kodify\BlogBundle\Domain\CommentInterface;
use Kodify\BlogBundle\Domain\CommentRepositoryInterface;

use Kodify\BlogBundle\Entity\Comment;

class CommentService extends AppService
{
    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * CommentService constructor.
     *
     * @param $commentRepository CommentRepositoryInterface
     * @param $formFactory FormFactoryInterface
     * @param $postService PostService
     */
    public function __construct($commentRepository, $formFactory, $postService)
    {
        $this->commentRepository = $commentRepository;
        $this->formFactory = $formFactory;
        $this->postService = $postService;
    }
    
    /**
     * Gets the latest comments published for a given post
     *
     * @param $postId int The id of the Post
     * @param $limit int The number of comments wanted
     * @param $offset int  Number of ordered comments to skip
     * @return array The latest comments of the post
     */
    public function getLatestByPost($postId, $limit, $offset = 0)
    {
        $post = $this->postService->findById($postId);
        if ($post === null) {
            return [];
        }
        return $this->commentRepository->getLatestByPost($post, $limit, $offset);
    }

    /**
     * Persists the given Comment
     *
     * @param $comment CommentInterface
     */
    public function persist($comment)
    {
        $this->commentRepository->persist($comment);
    }

    /**
     * Creates a form related to a Comment with the given options
     *
     * @param $commentType CommentType A form CommentType instance
     * @param $options array The given options to be passed to the form constructor
     * @return FormFactoryInterface
     */
    public function createForm($commentType, $options)
    {
        return $this->formFactory->create(
            $commentType,
            /**
             * TODO:: Remove the following dependency on a database entity
             * Don't know how to get around this using forms.
             * Also, creating an instance here makes it not properly untestable.
             */
            new Comment(),
            $options);
    }
}
