<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
	public function createAction($post, Request $request)
    {
        $comment = new Comment();
        $comment->setPost($this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($post));
        $form       = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl('create_comment', array('post' => $post)),
                'method' => 'POST',
            ]
        );

        $parameters = [
            'form'        => $form->createView(),
            'breadcrumbs' => ['home' => 'Home']
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('view_post', array('id' => $post)));
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}