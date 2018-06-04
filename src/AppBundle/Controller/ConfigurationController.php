<?php
namespace AppBundle\Controller;

use CNAMTS\PHPK\CoreBundle\Generator\Form\Bouton;
use AppBundle\Tableaux\ListeAssurePrn;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller; //??????
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConfigurationController extends AbstractController
{
    public function indexAction()
    {
		$request = $this->get('request');
		$ps=array('1','2','3');
		
		$EmmaRepo = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:EmmaAssure');		
        
		// Flux
		$TabFlux = $EmmaRepo->getFlux();
		$recup['FLUX']=array();
		foreach ($TabFlux as $value) 
		{
			$recup['FLUX']=$recup['FLUX']+array($value['FLUX'] =>$value['FLUX']);
		}
		
		// Civilité
        $TabCiv = $EmmaRepo->getCiv();
		$recup['CIV']=array();
		foreach ($TabCiv as $value) 
		{
			$recup['CIV']=$recup['CIV']+array($value['CIV'] =>$value['CIV']);
		}
		
		if(isset($_POST['formulaire_essai']['Civ'])){
			$civ = $_POST['formulaire_essai']['Civ'];
		}else{
			$civ = 'M';
		}
		
		
		$TabNom = $EmmaRepo->getNom($civ);
		$recup['NOM']=array();
		foreach ($TabNom as $value) 
		{
			$recup['NOM']=$recup['NOM']+array($value['NOM'] =>$value['NOM']);
		}
		
		
		$test = new Bouton(array('predefined'=>Bouton::PREDEFINED_TRANSMETTRE, 'text' => 'Test', 'id' => "idBtnTest"));
        $form = $this->get('form.factory')->create('AppBundle\Form\FormulaireEssai',$recup);
		
		
		//$tableau = $this->get('phpk_core.tableau')->get(new ListeAssurePrn());
		 $tableau = $this->get('phpk_core.tableau')->get(new ListeAssurePrn())->setRoute('app_index');
		//$tableau->getDataHandler()->getRepository('AppBundle:EmmaAssure')->setRepositoryMethod('listes');
	     $tableau->getDataHandler()->setRepository($this->getRepository('AppBundle:EmmaAssure','default'))
            ->setRepositoryMethod('listes');
		
		
		
		if ($form->handleRequest($request)->isValid()) {
			
		
			//$ident = $form["ident"]->getData(); //recupere la valeur de 'ident' dans le formulaire
			
			$flux = $form["Flux"]->getData();
			$civ = $form["Civ"]->getData();
			$nom = $form["Nom"]->getData();
			//die($nom);
			
			return $this->render('AppBundle:Configuration:configuration_detail.html.twig', array('flux' => $flux,'civ' => $civ, 'nom' => $nom));
			
		}
		
		  /* Création des boutons */
    
		return $this->render('AppBundle:Configuration:configuration.html.twig', array('form' => $form->createView(), 'ListeAssurePrn' => $tableau));
    }
	
	public function essaiAjaxAction($paramCiv){
		
		//die("controller ?????,");
		$EmmaRepo = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:EmmaAssure');
		
		// Mettre parametre civilité dans getNom (Par defautl c'est 'M')
		$TabNom = $EmmaRepo->getNom($paramCiv);
		$recup['NOM']=array();
		foreach ($TabNom as $value) 
		{
			$recup['NOM']=$recup['NOM']+array($value['NOM'] =>$value['NOM']);
			
		}
		//print_r($recup);
		
		//$request = $this->container->get['param'];
		$tab=  array('outpou','input','toto','naze');
		//$response = new JsonResponse ($tab[0]);
		$response = new JsonResponse ($recup['NOM']);
		
		//echo 'passage';
		//$data='passage';
		 //$response->send();
		//if($request->isXmlHttpRequest()){
		//print_r($response);	
		//};
	    return $response;
	}
	
	public function detailsAjaxAction($param){
		/**
		//$var = json_encode("c est ma variable");
		//die("controller !!!!");
		$param = new JsonResponse ("llaaaaaaa");
		return $param;**/
		$request = $this->get('request');
		$nir = "1530499410119";
		echo "<h1>".$nir."</h1>";
		$repository =  $this->container->get('doctrine')->getManager('default')->getRepository('AppBundle:EmmaAssure','default')/*->getAssurefromNir($nir)*/;

		$assure = $repository->getAssurefromNir($nir);
		$form = $this->get('form.factory')->create('AppBundle\Form\ModifAssure', $assure);
		//die($assure);

		if($form->handleRequest($request)->isValid())
        {

        }
		//$param = new JsonResponse ();
		return $this->render('AppBundle:Default:modifassure.html.twig', array('form' => $form->createView()));
	}
	
	
}
?>
