<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\PostCommentType;
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

    public function viewAction($id, Request $request)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }

        $formComment = new Comment();
        $formComment->setPost($currentPost);
        $form = $this->createForm(
            new PostCommentType(),
            $formComment,
            [
                'action' => $this->generateUrl('view_post', array('id' => $id)),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post'        => $currentPost,
            'form'        => $form->createView()
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Comment Created!';
        }

        //Get comments after form handling to avoid missing the new ones
        $comments = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->findBy(array('post' => $id));
        $parameters['comments'] = $comments;

        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
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
