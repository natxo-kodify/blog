<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CommentsController extends Controller
{
    public function indexAction()
    {
        $comments   = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->latest();
        $template   = 'KodifyBlogBundle:Comment:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home', 'comments' => 'Comments']];
        if (count($comments)) {
            $template               = 'KodifyBlogBundle:Comment:List/index.html.twig';
            $parameters['comments'] = $comments;
        }
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
            'breadcrumbs' => ['home' => 'Home', 'create_comment' => 'Create Comment']
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
