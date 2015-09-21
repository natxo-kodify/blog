<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    const LATEST = "latest";
    const RATE = "rated";

    public function indexAction($sort = self::RATE)
    {
        switch ($sort) {
            case self::RATE:
                $posts = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->sortRated();
                break;
            default:
            case self::LATEST:
                $posts = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->latest();
                break;
        }

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
        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post'        => $currentPost,
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

    public function rateAction($id, $star)
    {
        if ($this->getDoctrine()->getRepository("KodifyBlogBundle:Post")->setRate($id, $star)) {
            $this->get('session')->getFlashBag()->add('success', 'Post Rated!');
        } else {
            $this->get('session')->getFlashBag()->add('danger', 'Please rate your Post Correctly!');
        }

        $parameters = array(
            "id" => $id,
        );

        return $this->redirect($this->generateUrl("view_post", $parameters));
    }
}
