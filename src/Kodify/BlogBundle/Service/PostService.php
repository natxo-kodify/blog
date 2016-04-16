<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Entity\Post;

class PostService extends AppService
{
    /**
     * @return array The latest posts
     */
    public function getLatest() {
        return $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->latest();
    }

    /**
     * Gets a Post object given its id
     * @param $id int The id of the post
     * @return Post
     */
    public function findById($id) {
        return $this->getDoctrine()->getRepository('KodifyBlogBundle:Post')->find($id);
    }

    /**
     * Persists the given Post
     * @param $post Post
     */
    public function persist($post) {
        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();
    }
}