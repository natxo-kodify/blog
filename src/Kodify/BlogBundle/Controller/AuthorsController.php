<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Form\Type\AuthorType;
use Symfony\Component\HttpFoundation\Request;


class AuthorsController extends AppController
{
    /**
     * {@inheritdoc}
     */
    protected $services = [
        'app.author_service'
    ];

    public function indexAction()
    {
        $authors    = $this->get('app.author_service')->getLatest();
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
        $form       = $this->createForm(
            AuthorType::class,
            null,
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
            $this->get('app.author_service')->persist($author);
            $parameters['message'] = 'Author Created!';
        }

        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
