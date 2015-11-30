<?php
/**
 * Created by PhpStorm.
 * User: adrianr
 * Date: 30/11/15
 * Time: 20:10
 */

namespace Kodify\BlogBundle\Model\Handler;


use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Model\Command\CreatePostCommand;
use Kodify\BlogBundle\Repository\PostRepository;

class CreatePostHandler
{

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * CreatePostHandler constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function handle(CreatePostCommand $command)
    {
        $post = new Post($command->getAuthor(), $command->getTitle(), $command->getContent());

        try {
            $this->postRepository->save($post);
        } catch (\Exception $e) {
            throw new CommandHandlerException($e->getMessage(), 0, $e);
        }
    }

}