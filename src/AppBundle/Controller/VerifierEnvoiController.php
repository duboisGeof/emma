<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class VerifierEnvoiController extends AbstractController {

	public function indexAction(Request $request)
	{

		$repository =  $this->container->get('doctrine')->getManager('default')->getRepository('AppBundle:EmmaAssure','default');

		$assures = $repository->getAssureAndPRN3202();

		for ($i = 0; $i < count($assures); $i++){
			if(!$assures[$i]["DATE_ARR"] || !$assures[$i]["SUBRO"] || !$assures[$i]["FLUX"]){
				return $this->redirectToRoute('formu', array('missing_error' => true));
				break;
			}
        }
		return $this->redirectToRoute('imprime');
	}
}