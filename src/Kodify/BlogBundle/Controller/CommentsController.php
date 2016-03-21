<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
    public function indexAction($id)
    {
        $comments      = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->find($id);
        $template   = 'KodifyBlogBundle:Comment:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($comments)) {
            $template            = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['comments'] = $comments;
        }

        return $this->render($template, $parameters);
    }

    public function createAction(Request $request)
    {
        $form       = $this->createForm(
            new CommentType(),
            new Comment(),
            [
                'action' => $this->generateUrl('create_comment'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form'        => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_comment' => 'Publish Comment']
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Comment Published!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
