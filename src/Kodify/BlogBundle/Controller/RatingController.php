<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Rating;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RatingController extends Controller
{

    public function createAction()
    {
    	$request = $this->get('request');
    	$_rating = $request->request->get('rating');
    	$postId = $request->request->get('post_id');    	
    	$post = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($postId);
    	$user = $this->get('security.context')->getToken()->getUser();
    	$author = $this->getDoctrine()->getRepository('KodifyBlogBundle:Author')->findOneByName($user->getUsername());
    	$rating  = new Rating();
    	$rating->setPost($post);
    	$rating->setAuthor($author);
    	$rating->setRating($_rating);
		$em = $this->getDoctrine()->getManager();
    	$em->persist($rating);
    	$em->flush();
    	return new Response(json_encode(array('success' => 1, 'rating' => $_rating)));
    }

}
