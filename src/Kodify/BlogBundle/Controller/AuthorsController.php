<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Form\Type\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AuthorsController extends Controller
{
    public function indexAction()
    {
        $authors =  $this->get('kodify.repository.author')->latest();
        $template = 'KodifyBlogBundle:Author:List/empty.html.twig';
        $parameters = ['breadcrumbs' => ['home' => 'Home', 'authors' => 'Authors']];
        if (count($authors)) {
            $template = 'KodifyBlogBundle:Author:List/index.html.twig';
            $parameters['authors'] = $authors;
        }

        return $this->render($template, $parameters);
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(
            new AuthorType(),
            new Author(),
            [
                'action' => $this->generateUrl('create_author'),
                'method' => 'POST',
            ]
        );
        $parameters = [
            'form' => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_author' => 'Create Author'],
        ];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $author = $form->getData();
            $this->get('doctrine')->getManager()->persist($author);
            $this->get('doctrine')->getManager()->flush();
            $parameters['message'] = 'Author Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
