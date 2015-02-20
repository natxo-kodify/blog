<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    public function indexAction()
    {
        $posts      = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->latest();
        $template   = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template            = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    public function viewAction($id)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }
        $comments    = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->findBy(array('post' => $id));
        $parameters = [
            'breadcrumbs'   => ['home' => 'Home'],
            'post'          => $currentPost,
            'comments'      => $comments,
            'form_comment'  => $this->createCommentForm($currentPost),
        ];

        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
    }

    private function createCommentForm(Post $currentPost)
    {
        $comment    = new Comment();
        $form       = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl('comment_create'),
                'method' => 'POST',
            ]
        );
        return $form->createView();
    }

    public function createAction(Request $request)
    {
        $form       = $this->createForm(
            new PostType(),
            new Post(),
            [
                'action' => $this->generateUrl('create_post'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form'        => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_post' => 'Create Post']
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $post = $form->getData();
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Post Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
