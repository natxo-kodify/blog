<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Form\Type\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AuthorsController extends Controller
{
    public function indexAction()
    {
        $authors    = $this->get('author_service')->getLatest(8);
        $template   = 'KodifyBlogBundle:Author:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home', 'authors' => 'Authors']];
        if (count($authors)) {
            $template              = 'KodifyBlogBundle:Author:List/index.html.twig';
            $parameters['authors'] = $authors;
        }

        return $this->render($template, $parameters);
    }

    public function createAction(Request $request)
    {
        $form       = $this->get('author_service')->createForm(
            new AuthorType(),
            [
                'action' => $this->generateUrl('create_author'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form'        => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_author' => 'Create Author']
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $author = $form->getData();
            $this->get('author_service')->persist($author);
            $parameters['message'] = 'Author Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
