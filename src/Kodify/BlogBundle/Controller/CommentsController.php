<?php


namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Entity\Post;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
    public function createAction(Request $request, $id)
    {
        $currentPost = $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
        $comment = new Comment();
        $comment->setPost($currentPost);

        if (!$currentPost instanceof Post) {
            throw $this->createNotFoundException('Post not found');
        }

        $form = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl('create_comment', ['id' => $currentPost->getId()]),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form' => $form->createView(),
            'breadcrumbs' => [
                [
                    'anchor' => 'Home',
                    'path' => 'home',
                ],
                [
                    'anchor' => $currentPost->getTitle(),
                    'path' => 'view_post',
                    'params' => [
                        'id' => $currentPost->getId()
                    ]
                ],
                [
                    'anchor' => 'Create Comment',
                    'path' => 'create_comment',
                    'params' => [
                        'id' => $currentPost->getId()
                    ]
                ]
            ]
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $parameters['message'] = 'Comment Created!';

            return $this->redirectToRoute('view_post', ['id' => $currentPost->getId()]);
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}