<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
    public function createAction(Request $request)
    {
        $post_id = $request->attributes->get('id');

        $form       = $this->createForm(
            new CommentType(),
            new Comment(),
            [
                'action' => $this->generateUrl('create_comment', array('id' => $post_id)),
                'method' => 'POST',
                'data'   => array('post_id' => $post_id),
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
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
