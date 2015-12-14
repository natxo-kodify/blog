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
        $post = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($request->query->get('postId'));
        $user = $this->get('security.context')->getToken()->getUser();
        $author = $this->getDoctrine()->getRepository('KodifyBlogBundle:Author')->findOneByName($user->getUsername());
        $comment  = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($author);
        $request = $this->getRequest();
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);
        if ($form->isValid()) {
        	$em = $this->getDoctrine()->getManager();
        	$em->persist($comment);
        	$em->flush();        	
            return $this->redirect($this->generateUrl('view_post', array('id' => $comment->getPost()->getId())));
        } else {
        	die($form->getErrorsAsString());
        }

    }

}
