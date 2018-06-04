<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController {

	public function indexAction(Request $request)
	{
		return $this->render('AppBundle:Default:index.html.twig');
	}
}
