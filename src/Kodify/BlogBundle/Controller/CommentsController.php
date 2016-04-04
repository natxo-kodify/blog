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
        $comment = new Comment();
        $comment->setCreatedAt( new \DateTime('now'));
        $comment->setUpdatedAt( new \DateTime('now'));

        $postId = $request->query->get('id');

        $post = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($postId);
        $comment->setPost($post);

        $form       = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl('create_comment', array('id' => $postId)),
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
