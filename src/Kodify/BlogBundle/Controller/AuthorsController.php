<?php

namespace Kodify\BlogBundle\Controller;

use Kodify\BlogBundle\Form\Type\AuthorType;
use Kodify\BlogBundle\Model\Command\CreateAuthorCommand;
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
        $createUserCommand = new CreateAuthorCommand();
        $form = $this->createForm(
            new AuthorType(),
            $createUserCommand,
            [
                'action' => $this->generateUrl('create_author'),
                'method' => 'POST',
            ]
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('tactician.commandbus')->handle($createUserCommand);
            $this->get('session')->getFlashBag()->add('success', 'Author created!');
            return $this->redirectToRoute('authors');
        }

        $parameters = [
            'form' => $form->createView(),
            'breadcrumbs' => ['home' => 'Home', 'create_author' => 'Create Author'],
        ];
        return $this->render('KodifyBlogBundle:Default:create.html.twig', $parameters);
    }
}
