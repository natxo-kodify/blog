<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostsController extends Controller
{
    public function indexAction(Request $request)
    {
		
		$orderby = $request->get('order');
		$postRepository = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post');
		if ($orderby == 'rating') {
			$postRepository->setOrderBy(['avgRating' => 'DESC', 'createdAt'=>'DESC']);	
		}
		
		$posts      = $postRepository->latest();
		$template   = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template            = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }
		
        return $this->render($template, $parameters);
    }

    public function viewAction($id, Request $request)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }
        
		$comments = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->findBy(['post'=> $currentPost]);
		
		$add_comment_form = $this->createForm( 
			new CommentType(), 
			new Comment(), 
			[
				'action' => $this->generateUrl('view_post',['id'=> $id]),
				'method' => 'POST'
			]
		);
		
		$parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post'        => $currentPost,
			'comments' 	  => $comments, 
			'start_rating' => 1,
			'end_rating' => 5,
			'add_comment_form' => $add_comment_form->createView()
        ];
		
		$add_comment_form->handleRequest($request);
		if ($add_comment_form->isValid()) {
            $comment = $add_comment_form->getData();
			$comment->setPost($currentPost);
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
			
			$parameters['comments'][] = $comment; //@ToDo - Nasty fix ??
        }
		
        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
    }

    public function createAction(Request $request)
    {
        $form       = $this->createForm(
            new PostType(),
            new Post(),
            [
                'action' => $this->generateUrl('create_post'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form'        => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_post' => 'Create Post']
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $post = $form->getData();
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Post Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
	
	public function addRatingAction($id, Request $request) {
		try{ 
			$currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
			if (!$currentPost instanceof Post) {
				throw $this->createNotFoundException('Post not found');
			}
			
			$rating = $request->get('rating');
			if ($rating != (int)$rating) {
				throw new \Exception('Invalid value for rating');
			}
			
			$new_rating = $currentPost->addToRating($rating);
			
			$this->getDoctrine()->getManager()->persist($currentPost);
			$this->getDoctrine()->getManager()->flush();
		} catch( \Exception $ex) {
			return new JsonResponse(array('error' => $ex->getMessage()));
		}
		return new JsonResponse(array('new_rating'=> $new_rating));
	}
	
}
