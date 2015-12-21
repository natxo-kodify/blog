<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    public function createAction($postId, Request $request)
    {
        $post = $this->getDoctrine()
            ->getRepository('KodifyBlogBundle:Post')
            ->find($postId);
        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl('create_comment', array('postId' => $postId)),
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
            $parameters['message'] = 'Comment Created!';
        } elseif ($form->isSubmitted()) {
            $parameters['message'] = 'Error creating the comment! ';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
