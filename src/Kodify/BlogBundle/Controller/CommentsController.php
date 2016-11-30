<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
    public function createAction($id, Request $request)
    {
        $post = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        $comment = new Comment();
        $comment->setPost($post);

        $form       = $this->createForm(
            new CommentType(),
            $comment
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect($this->generateUrl('view_post', array('id' => $id)));
    }
}
