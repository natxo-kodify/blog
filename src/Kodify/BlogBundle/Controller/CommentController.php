<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    public function indexAction()
    {
        
    }

    
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
            'form'        => $form->createView()/*,
            'breadcrumbs' => ['home' => 'Home', 'create_comment' => 'Create Comment']*/
        ];

        $form->handleRequest($request);
        
        if($request->getMethod() == 'POST'){


            if ($form->isValid()) {
                $newComment = $form->getData();
                
                $post = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
                $newComment->setPost($post);

                $this->getDoctrine()->getManager()->persist($newComment);
                $this->getDoctrine()->getManager()->flush();
                $parameters['message'] = 'Comment Created!';

                return $this->redirectToRoute('view_post', array('id'=>$id), 301);
            }

        }
        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
    
}
