<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CommentController extends Controller
{
    /**
    * indexAction
    *
    * That method is just a redirect in case for some reason a user checks /comments url
    * (as happened before the fix of the logo url)
    */
    public function indexAction()
    {
        return $this->redirectToRoute('home', array(), 301);
    }

    /**
    * createAction
    *
    * Shows the form and creates new comment
    */
    public function createAction(Request $request, $id)
    {
        $form = $this->createForm(
            new CommentType(),
            new Comment(),
            [
                'action' => $this->generateUrl('create_comment',array('id' => $id)),
                'method' => 'POST',
            ]
        );

        $parameters = [
            'form'        => $form->createView(),
            'breadcrumbs' => ['home' => 'Home'],
        ];

        $form->handleRequest($request);
        
        if ($request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $newComment = $form->getData();
                
                $post = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
                $newComment->setPost($post);

                $this->getDoctrine()->getManager()->persist($newComment);
                $this->getDoctrine()->getManager()->flush();
                $parameters['message'] = 'Comment Created!';

                return $this->redirectToRoute('view_post', array('id'=>$id), 301);
            }

            $parameters['message'] = 'Comment could not be created. The data is not valid! Try it again.';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
    
}
