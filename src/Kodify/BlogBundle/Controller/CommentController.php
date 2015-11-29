<?php

namespace Kodify\BlogBundle\Controller;


use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller
{

    public function createAction(Request $request)
    {

        $postRepository = $this->get('kodify.repository.post');
        $postId = $request->get('id');
        $post = $postRepository->find($postId);

        if (!$post) {
            throw new NotFoundHttpException('Post not found');
        }

        $comment = new Comment();
        $comment->setPost($post);
        $options = [
            'action' => $this->get('router')->generate('comment_post', ['id' => $postId]),
        ];
        $form = $this->get('form.factory')->create(new CommentType(), $comment, $options);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $em->persist($comment);
            $em->flush();

            $postId = $comment->getPost()->getId();

            return $this->redirectToRoute('view_post', ['id' => $postId]);
        }

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'form' => $form->createView(),
        ];
        $template = 'KodifyBlogBundle:Default:create.html.twig';

        return $this->render($template, $parameters);
    }


}