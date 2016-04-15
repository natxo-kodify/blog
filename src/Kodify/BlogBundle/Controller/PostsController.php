<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Entity\Rating;
use Kodify\BlogBundle\Form\Type\CommentType;
use Kodify\BlogBundle\Form\Type\PostType;
use Kodify\BlogBundle\Form\Type\RatingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PostsController extends Controller
{
    public function indexAction()
    {
        $posts = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->latest();
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
        $currentPost = $this->loadPost($id);
        $commentForm = $this->generateCommentForm($id);
        $ratingForm = $this->generateRatingForm($id);

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post' => $currentPost,
            'comment_form' => $commentForm->createView(),
            'rating_form' => $ratingForm->createView(),
        ];

        /** @var Session $session */
        $session = $this->get('session');
        if ($message = $session->getFlashBag()->get('post_message')) {
            $parameters['message'] = array_pop($message);
        }

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

    public function addRatingAction($postId, Request $request)
    {
        $currentPost = $this->loadPost($postId);

        $ratingForm = $this->generateRatingForm($postId);
        $ratingForm->handleRequest($request);

        if ($ratingForm->isValid()) {
            $rating = $ratingForm->getData();
            $rating->setPost($currentPost);
            $this->getDoctrine()->getManager()->persist($rating);
            $this->getDoctrine()->getManager()->flush();

            $session = $this->get('session');
            $session->getFlashBag()->set('post_message', 'Rating added!');
        }

        return $this->redirectToRoute('view_post', ['id' => $postId]);
    }

    public function addCommentAction($postId, Request $request)
    {
        $currentPost = $this->loadPost($postId);

        $ratingForm = $this->generateRatingForm($postId);

        $commentForm = $this->generateCommentForm($postId);
        $commentForm->handleRequest($request);

        $parameters = [
            'breadcrumbs' => ['home' => 'Home'],
            'post' => $currentPost,
            'comment_form' => $commentForm->createView(),
            'rating_form' => $ratingForm->createView(),
        ];

        if ($commentForm->isValid()) {
            $comment = $commentForm->getData();
            $comment->setPost($currentPost);
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            $session = $this->get('session');
            $session->getFlashBag()->set('post_message', 'Comment added!');

            return $this->redirectToRoute('view_post', ['id' => $postId]);
        }

        return $this->render('KodifyBlogBundle::Post/view.html.twig', $parameters);
    }

    /**
     * @param int $postId
     * @return Form
     */
    private function generateCommentForm($postId)
    {
        return $this->createForm(
            new CommentType(),
            new Comment(),
            [
                'action' => $this->generateUrl('add_comment', ['postId' => $postId]),
                'method' => 'POST',
            ]
        );
    }

    /**
     * @param int $postId
     * @return Form
     */
    private function generateRatingForm($postId)
    {
        return $this->createForm(
            new RatingType(),
            new Rating(),
            [
                'action' => $this->generateUrl('add_rating', ['postId' => $postId]),
                'method' => 'POST',
            ]
        );
    }

    /**
     * @param $id
     * @return object
     */
    private function loadPost($id)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }
        return $currentPost;
    }
}
