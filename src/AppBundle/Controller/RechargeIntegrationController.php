<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class RechargeIntegrationController extends AbstractController {



	public function indexAction(Request $request)
	{

		$repository =  $this->container->get('doctrine')->getManager('default')->getRepository('AppBundle:EmmaAssure','default');

		if($repository->verifPremiereIntegration()){
			if($repository->verifSemaineDerniere()){
				//Enleve les prn3202 de la semaine dernière OK
				$repository->nettoieSemaineDerniere();
				//Extrait dans W_PRN_3202 depuis FEND_EXTRACT les matricule de la semaine dernière OK
				$repository->extraitSemaineDerniere();
				//Integre les assurés ré-extraits dans prn3202 et les supprime de W_PRN_3202 OK
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://55.166.4.14/Ahmed/geca_v3/src/AppBundle/scripts/Requete_IJ.php?reint=1");
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$output = curl_exec($ch);
				curl_close($ch);
				//$requete_ij = file_get_contents('http://55.166.4.14/Ahmed/geca_v3/src/AppBundle/scripts/Requete_IJ.php?reint=1');
			}
		}

		return $this->redirectToRoute('formu');
	}
}
