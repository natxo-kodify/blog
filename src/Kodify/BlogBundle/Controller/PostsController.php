<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    public function indexAction(Request $request)
    {
    	$orderBy = $request->query->get('rating');
		
    	if($orderBy){
       		$posts = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->getPostsOrderBy();
    	} else {
    		$posts = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->latest();
    	}

        foreach($posts as $post){
        	$post->setRating($this->getPostRating($post->getId()));
        }

        $template = 'KodifyBlogBundle:Post:List/empty.html.twig';       
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    public function viewAction($id)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }
        $comments = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->getCommentsForPost($id);
        $form = $this->createForm(
        	new CommentType()
        	,new Comment()
        	,[
        		 'action' => $this->generateUrl('create_comment')
        		,'method' => 'POST'
        	]
        );

        $user = $this->get('security.context')->getToken()->getUser();
        $author = $this->getDoctrine()->getRepository('KodifyBlogBundle:Author')->findOneByName($user->getUsername());
        
        $parameters = [
             'breadcrumbs' => ['home' => 'Home']
            ,'form' => $form->createView()
            ,'post' => $currentPost
            ,'total_rating' => $this->getPostRating($id)
            ,'rated' => $this->getDoctrine()->getRepository('KodifyBlogBundle:Rating')->checkIfUserHasRateIt($id, $author)
            ,'comments' => $comments
        ];

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
    
    private function getPostRating($id)
    {
    	$post_ratings = $this->getDoctrine()->getRepository('KodifyBlogBundle:Rating')->getRatingsForPost($id);
    	$total_rating = 0;
    	foreach($post_ratings as $rating){
    		$total_rating += $rating->getRating();
    	}
    	return round($total_rating / count($post_ratings));
    }
}

