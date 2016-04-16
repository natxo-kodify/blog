<?php

namespace Kodify\BlogBundle\Service;

use Kodify\BlogBundle\Entity\Author;

class AuthorService extends AppService
{
    /**
     * @return array The latest posts
     */
    public function getLatest() {
        return $this->getDoctrine()->getRepository('KodifyBlogBundle:Author')->latest();
    }

    /**
     * Persists the given Author
     *
     * @param $author Author
     */
    public function persist($author) {
        $this->getDoctrine()->getManager()->persist($author);
        $this->getDoctrine()->getManager()->flush();
    }
}