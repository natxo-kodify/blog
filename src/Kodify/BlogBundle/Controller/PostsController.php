<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostsController
 * @package Kodify\BlogBundle\Controller
 */
class PostsController extends Controller
{


    /**
     * Lists all posts
     *
     * @param string $sort sort type
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($sort = PostRepository::SORT_LATEST)
    {

        $posts = $this->get("kodify_blog.sort")->sortPostsBy($sort);
        $template   = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template            = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    /**
     * Views a post
     *
     * @param $id post id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Creates a post
     *
     * @param Request $request request of the form
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Rates a post
     *
     * @param $id post
     * @param $star number of stars
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
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
