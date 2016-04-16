<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends AppController
{
    /**
     * {@inheritdoc}
     */
    protected $services = [
        'app.post_service'
    ];
    
    public function indexAction()
    {
        $posts      = $this->get('app.post_service')->getLatest();
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
        $currentPost = $this->get('app.post_service')->findById($id);
        if ($currentPost === null) {
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
            PostType::class,
            null,
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
            $this->get('app.post_service')->persist($post);
            $parameters['message'] = 'Post Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
