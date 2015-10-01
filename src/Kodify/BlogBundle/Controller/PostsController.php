<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    public function indexAction()
    {
        $posts      = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->latest();
        $template   = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template            = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    public function viewAction($id)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        $listComment = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->findByPost($id);

        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }
        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post'        => $currentPost,
            'listComment' => $listComment,  
        ];

        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
    }

    public function rateAction($id, $rate)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        $listComment = $this->getDoctrine()->getRepository('KodifyBlogBundle:Comment')->findByPost($id);

        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }

        //Calculating the new rate
        $currentPost->setRate(($rate + $currentPost->getNbrate()*$currentPost->getRate())/($currentPost->getNbrate() + 1)); 
        $currentPost->setNbrate(($currentPost->getNbrate())+1);

        $this->getDoctrine()->getManager()->persist($currentPost);
        $this->getDoctrine()->getManager()->flush();

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post'        => $currentPost,
            'listComment' => $listComment,  
        ];
        return $this->redirect($this->generateUrl( 'view_post',array( 'id' => $id ) ) );
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
}
