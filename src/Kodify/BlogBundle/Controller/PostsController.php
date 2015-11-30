<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\PostRating;
use Kodify\BlogBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends Controller
{
    public function indexAction(Request $request)
    {
        $postRepository = $this->get('kodify.repository.post');

        if($request->query->get('order') == 'rating') {
            $posts = $postRepository->bestRated();
        } else {
            $posts = $postRepository->latest();
        }

        $template = 'KodifyBlogBundle:Post:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        if (count($posts)) {
            $template = 'KodifyBlogBundle:Post:List/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    public function viewAction(Request $request, $id)
    {
        if (0 !== (integer)$id) {
            $currentPost = $this->get('kodify.repository.post')->find($id);
        } else {
            $currentPost = $this->get('kodify.repository.post')->findOneBy(['title' => $id]);
        }

        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post' => $currentPost,
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

    public function rateAction(Request $request)
    {
        $postId = $request->get('id');
        $postRepository = $this->get('kodify.repository.post');

        /** @var Post|null $post */
        $post = $postRepository->find($postId);

        $responseData = ['message' => 'Post not found'];

        if (!$post) {
            return new JsonResponse($responseData, 404);
        }

        $userRating = $request->get('rating');
        $postRating = new PostRating();
        $postRating->setPost($post);
        $postRating->setValue($userRating);

        $post->addRating($postRating);
        $em = $this->get('doctrine')->getManager();
        $em->persist($post);
        $em->flush();

        $responseData = [
            'message' => 'Rating added successful',
            'rating' => $post->rating(),
        ];

        return new JsonResponse($responseData, 201);

    }
}
