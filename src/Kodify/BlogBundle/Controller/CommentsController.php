<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CommentsController extends Controller
{
    public function createAction(Request $request, $post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('Kodify\BlogBundle\Entity\Post')->find($post_id);
        $comment = new Comment();
        $comment->setPost($post);

        $form       = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl('create_comment', ['post_id' => $post_id]),
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
            var_dump($comment);
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Comment Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
