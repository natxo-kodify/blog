<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\PostRatingType;
use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    public function indexAction($sort)
    {
        $posts = $this->getPosts($sort);

        $template = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home'],'sort' => $sort];
        if (count($posts)) {
            $template = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    public function viewAction(Request $request, $id)
    {
        $currentPost = $this->get('kodify_blog.post.repository')->find($id);

        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }

        $form = $this->createForm(new PostRatingType());

        if ($request->getMethod() === Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $this->get('kodify_blog.post_rater')->rate($currentPost, $data['rating']);

                return $this->redirect($this->generateUrl('view_post', array('id' => $id)));
            }
        }

        $rating = $this->get('kodify_blog.post_rating.calculator')->getRatingForPost($currentPost);

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post' => $currentPost,
            'rating' => $rating,
            'form' => $form->createView(),
        ];

        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(
            new PostType(),
            new Post(),
            [
                'action' => $this->generateUrl('create_post'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form' => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_post' => 'Create Post'],
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
     * @param $sort
     *
     * @return array|mixed
     */
    private function getPosts($sort)
    {
        switch ($sort) {
            case Post::ORDER_RATING:
                $posts = $this->get('kodify_blog.post.repository')->highestRated();
                break;
            case Post::ORDER_DATE:
            default:
                $posts = $this->get('kodify_blog.post.repository')->latest();

                return $posts;
        }

        return $posts;
    }
}
