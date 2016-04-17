<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{

    public function createAction($postId)
    {
        $form = $this->getCreateForm($postId);

        //Is the same view as the post one, just with the form appended
        return $this->renderPostsView($postId, [
            'formCreateComment' => $form->createView()
        ]);
    }

    public function postCreateAction(Request $request, $postId)
    {
        $form = $this->getCreateForm($postId);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('comment_service')->persistForm($form);
        }

        return $this->redirect($this->generateUrl(
            'view_post', ['id' => $postId]
        ));
    }

    /**
     * Creates a form to create a comment
     *
     * @param $postId int The id of the post the comment will belong to
     * @return FormInterface
     */
    protected function getCreateForm($postId)
    {
        return $this->get('comment_service')->createForm(
            new CommentType(),
            [
                'action' => $this->generateUrl('create_comment', ['postId' => $postId]),
                'method' => 'POST',
            ]
        );
    }

    /**
     * Generates the view to be rendered through the PostController view action
     *
     * @param int $postId Id of the Post to be rendered
     * @param array $parameters Additional parameters to be passed to the view
     * @return \Symfony\Component\HttpFoundation\Response The view to be rendered
     */
    protected function renderPostsView($postId, $parameters = []) {
        $postController = new PostsController();
        $postController->setContainer($this->container);
        return $postController->viewAction($postId, $parameters);
    }
}
