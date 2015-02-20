<?php

namespace Kodify\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Kodify\BlogBundle\Entity\Comment;

/**
 * Comment controller.
 *
 */
class CommentController extends Controller
{
    /**
     * Creates a new Comment entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Comment();
        $em     = $this->getDoctrine()->getManager();
        $raw_data = $request->request->getIterator()['comment'];

        $entity->initializeCreatedAt();
        $entity->markAsUpdated();
        $author = $em->getRepository('KodifyBlogBundle:Author')->findOneBy(array('id' => $raw_data['author']));
        $post   = $em->getRepository('KodifyBlogBundle:Post')->findOneBy(array('id' => $raw_data['post']));

        $entity->setContent($raw_data['content']);
        $entity->setAuthor($author);
        $entity->setPost($post);

        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('view_post', array('id' => $raw_data['post'])));
    }
}
