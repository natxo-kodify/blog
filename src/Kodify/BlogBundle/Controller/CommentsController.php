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
    	$comment	= new Comment();
		if($request->get('post')){
			$post 		= $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find( $request->get('post') );
			$comment->setPost( $post );
		}
    	$form       = $this->createForm(
            new CommentType(),
            $comment,
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
        	$date = new \DateTime('now');
			$updateTime = $date->format('Y-m-d H:i:s');
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Comment Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }

}
