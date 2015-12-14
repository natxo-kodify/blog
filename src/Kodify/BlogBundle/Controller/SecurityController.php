<?php

namespace Kodify\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
    {	
    	$authenticationUtils = $this->get('security.authentication_utils');
    	$error = $authenticationUtils->getLastAuthenticationError();
    	 
    	$parameters = [
	    	'breadcrumbs' => ['login' => 'Login'],
	    	'error' => $error,
    	];
    	
		return $this->render('KodifyBlogBundle::Security/login.html.twig', $parameters);

	}
	
	public function loginCheckAction()
	{

	}
}
