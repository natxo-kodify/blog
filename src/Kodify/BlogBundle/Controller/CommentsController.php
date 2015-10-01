<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Kodify\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
    public function createAction(Request $request, $postid)
    {
        $form       = $this->createForm(
            new CommentType(),
            new Comment(),
            [
                'action' => $this->generateUrl('create_post'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form'        => $form->createView(),
            'postid'      => $postid,
            'breadcrumbs' => ['home' => 'home', 'createcomment' => 'Create Comment']
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($postid)); //set the post
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl( 'view_post',array( 'id' => $postid ) ) );
        }

        return $this->render('KodifyBlogBundle:Comment:create.html.twig', $parameters);
    }
}
