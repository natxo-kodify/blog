<?php

namespace Kodify\BlogBundle\Service;

//TODO:: use Kodify\BlogBundle\Entity\Comment;
//TODO:: use Kodify\BlogBundle\Form\Type\CommentType;
//TODO:: use Kodify\BlogBundle\Repository\CommentRepository;
use Kodify\BlogBundle\Tests\Fixtures\CommentsFixture;
use Kodify\BlogBundle\Tests\Fixtures\PostsFixture;
use Symfony\Component\Form\FormInterface;


class CommentService extends AppService
{
    /**
     * @var mixed //TODO:: CommentRepository
     */
    private $commentRepository;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * CommentService constructor.
     *
     * @param $commentRepository mixed //TODO:: CommentRepository
     * @param $postService PostService
     */
    public function __construct($commentRepository = null, $postService)
    {
        $this->commentRepository = $commentRepository;
        $this->postService = $postService;
    }
    
    /**
     * Gets the latest comments published for a given post
     *
     * @param $postId int The id of the Post
     * @param $limit int The number of comments wanted
     * @return array The latest comments of the post
     */
    public function getLatestByPost($postId, $limit) {
        $post = $this->postService->findById($postId);
        if ($post === null) {
            return [];
        }

        //TODO:: Refactor this
        if ($post->getId() == PostsFixture::WAY_ID) {
            return [
                ['text' => CommentsFixture::NICE]
            ];
        } else {
            return [];
        }
    }

    /**
     * Gets a Comment object given its id
     * @param $id int The id of the comment
     * @return mixed //TODO:: Comment
     */
    public function findBy($id) {
        //TODO:: return $this->commentRepository->find($id);
    }

    /**
     * Persists the given Comment
     *
     * @param $comment mixed //TODO:: Comment
     */
    public function persist($comment) {
        //TODO:: $this->commentRepository->persist($comment);
    }

    /**
     * Creates a form related to a Comment with the given options
     *
     * @param $commentType mixed //TODO::CommentType A form CommentType instance
     * @param $options array The given options to be passed to the form constructor
     * @return FormInterface
     */
    public function createForm($commentType, $options) {
        //TODO::
//        return $this->container->get('form.factory')->create(
//            $commentType,
//            new Comment(),
//            $options);
    }
}
