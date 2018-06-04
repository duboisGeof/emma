<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ModifAssureController extends AbstractController {



	public function indexAction(Request $request, $nir)
	{
		echo "<h1>".$nir."</h1>";
		$repository =  $this->container->get('doctrine')->getManager('default')->getRepository('AppBundle:EmmaAssure','default')/*->getAssurefromNir($nir)*/;

		$assure = $repository->getAssurefromNir($nir);

		/*chmod -R 777 search.sh
		C931-DECERF-00176
		7MSJSFFJ*/
		//print_r($assure);
		$form = $this->get('form.factory')->create('AppBundle\Form\ModifAssure', $assure);

		if($form->handleRequest($request)->isValid())
        {

        }

		return $this->render('AppBundle:Default:modifassure.html.twig', array('form' => $form->createView()));
	}
}
