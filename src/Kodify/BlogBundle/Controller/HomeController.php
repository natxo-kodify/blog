<?php

namespace Kodify\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $posts = $this->getPostRepository()->latest();

        $parameters = ['breadcrumbs' => ['home' => 'Home']];
        $template = 'KodifyBlogBundle:Post:List/empty.html.twig';
        if (count($posts)) {
            $template = 'KodifyBlogBundle::Home/index.html.twig';
            $parameters['posts'] = $posts;
        }

        return $this->render($template, $parameters);
    }

    /**
     * TODO: Next step, inject dependencies like this into controller
     * @return PostRepository
     */
    private function getPostRepository()
    {
        return $this->getDoctrine()->getRepository('KodifyBlogBundle:Post');
    }
}