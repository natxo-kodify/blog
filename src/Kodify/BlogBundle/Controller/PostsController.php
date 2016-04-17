<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    public function indexAction()
    {
        $posts      = $this->get('post_service')->getLatest(5);
        $template   = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template            = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    /**
     * Returns a view of the given post
     *
     * @param $id int The id of the post to view
     * @param array $additionalParams additional params to send to the view
     * @return Response
     */
    public function viewAction($id, $additionalParams = [])
    {
        $currentPost = $this->get('post_service')->findById($id);
        if ($currentPost === null) {
            throw $this->createNotFoundException('Post not found');
        }
        $parameters = array_merge([
            'breadcrumbs' => ['home' => 'Home'],
            'post'        => $currentPost,
            'comments'    => $this->get('comment_service')->getLatestByPost($id, 5)
        ], $additionalParams);

        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
    }

    public function createAction(Request $request)
    {
        $form       = $this->get('post_service')->createForm(
            new PostType(),
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
            $this->get('post_service')->persistForm($form);
            $parameters['message'] = 'Post Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
