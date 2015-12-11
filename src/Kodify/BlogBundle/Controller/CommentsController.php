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
        $post = $this->getPost($request->query->get('postId'));

        $comment  = new Comment();
        $comment->setPost($post);
        $request = $this->getRequest();
        $form    = $this->createForm(new CommentType(), $comment);
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

    protected function getPost($postId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $post = $em->getRepository('KodifyBlogBundle:Post')->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find Post.');
        }

        return $post;
    }
}
