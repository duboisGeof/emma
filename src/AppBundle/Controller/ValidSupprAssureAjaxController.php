<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidSupprAssureAjaxController extends AbstractController {

	public function indexAjaxAction(Request $request)
	{

		$repository =  $this->container->get('doctrine')->getManager('default')->getRepository('AppBundle:EmmaAssure','default')/*->getAssurefromNir($nir)*/;

		$sql_response = $repository->deleteAssurefromNir($_POST['nir']);


		$reponse = new JsonResponse($sql_response);

		return $reponse;
	}
}
