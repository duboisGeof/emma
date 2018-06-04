<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Datetime;
use Alchemy\Ghostscript\Transcoder;
use AppBundle\scripts\Fonction;
use AppBundle\Entity\EmmaEnvoi;


class ImpressionController extends AbstractController
{
	public function imprimeAction(Request $request)
	{
		//récupérer la liste des assurés concernés par les courriers
		$repository_liste_Assures_courrier = $this->getDoctrine()->getManager()->getRepository('AppBundle:EmmaAssure','default');
		$tab_liste_Assures_courrier=$repository_liste_Assures_courrier->ListeAssuresCourriers();
		
		$pdf2 = new \FPDI();
		$chemin=$this->container->getParameter('chemin_courrier3202');
		$cheminTiff=$this->container->getParameter('chemin_courrier3202Tiff');
		$nb_courrier=0;
		// Est utilisé dans le nom du tiff pour les différencier
		$i = 1;
		
		// Vide le repertoire tiff/pdf
		$function = new Fonction();
		$function->vider_repertoire($cheminTiff);
		$function->vider_repertoire($chemin);
		
		// Connection au serveur ftp pour mettre les tiffs dans diademe
		$ftp = ftp_connect("55.166.4.149", 21) or exit('Erreur : connexion au serveur FTP impossible.');
		ftp_login($ftp, "CNAMTS\C119301-DIADEME", "P@ssDi@deme931");
		// Suppression des anciens tiff dans le ftp
		$function->vider_ftp($ftp, '/Emma');
		
		foreach ($tab_liste_Assures_courrier as $value) 
		{	
			$nb_courrier=$nb_courrier+1;
			$pdf = new \FPDI();
			$recup_nir=$value['NIR'];
			$recup_cle=$value['CLE'];
			$recup_nom=$value['NOM'];
			$recup_prenom=$value['PRENOM'];
			$recup_civ=$value['CIV'];
			$recup_cpl_adr=$value['CPL_ADRESSE'];
			$recup_adr=$value['ADRESSE'];
			$recup_cp_commune=$value['CP'].' '.$value['COMMUNE'];
			
			//insertion des paramètres pour courriers séparés
			$pdf=$this->CreePDF($pdf,$recup_civ,$recup_nom,$recup_prenom,$recup_cpl_adr,$recup_adr,$recup_cp_commune,$recup_nir,$recup_cle);
			
			//insertion des paramètres pour courriers regroupés
			$pdf2=$this->CreePDF($pdf2,$recup_civ,$recup_nom,$recup_prenom,$recup_cpl_adr,$recup_adr,$recup_cp_commune,$recup_nir,$recup_cle);

			$ficpdf=$chemin.'courriers'.$recup_nir.date('Ymd').'.pdf';
			$pdf->Output($ficpdf, 'F'); //pour enregistrer courrier séparément
			
			
			
			//Mettre une date d'envoi dans la table prn_3202
			$repository_ajout_date_envoi = $this->getDoctrine()->getManager()->getRepository('AppBundle:Prn3202','default');
			$result_update=$repository_ajout_date_envoi->update_apres_courrier($recup_nir);
			
			
			// Geoffrey - 25/04/2018
			// Creation des tif via les pdf qui viennent d etre crées (se trouvent dans le dossier tif dans web)
			// Bundle utilisé : Ghostscript (Télécharger via composer)
			
			$courrierTif = $cheminTiff.date('Ymdhis').'_EMMA_'.$i.'.tif';
			fopen($courrierTif, 'w');
			chmod($courrierTif, 0777);
			
			$transcoder = \Ghostscript\Transcoder::create();
			//die('pdf/3202/'.'courriers'.$recup_nir.date('Ymd').'.pdf');
			$transcoder->toImage($chemin.'courriers'.$recup_nir.date('Ymd').'.pdf', $courrierTif);
			ftp_put($ftp, "Emma/".date('Ymdhis').'_EMMA_'.$i.'.tif', $courrierTif, FTP_BINARY);
			$i++;
		}	
		
		ftp_close($ftp);
		

		$ficpdf2=$chemin.'courriers'.date('Ymd').'.pdf';
		$pdf2->Output($ficpdf2, 'F'); //pour enregistrer courrier regroupés*/
		
		//ajouter une ligne du nb de courriers envoyés
		//$repository_nb_courrier = $this->getDoctrine()->getManager()->getRepository('AppBundle:EMMA_ENVOI','default');
				
		//insertion dans la table EMMA_ENVOI
		// Pour pouvoir utilisé cette entité, il faut soit changer le format de la date et la mettre en string
		// soit faire une requete native comme plus bas
		/**$today = new DateTime('now');
		$emmaEnvoi = new EmmaEnvoi();
		//Objet
		//die(gettype($today));
		$emmaEnvoi->setdateEnvoi($today->format('Y-m-d H:i:s'));
		$emmaEnvoi->settypeEnvoi('courrier');
		$emmaEnvoi->setappli('3202');
		$emmaEnvoi->setnb($nb_courrier);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($emmaEnvoi);
		$em->flush();**/
		//****************************************
		//insertion dans la table EMMA_ENVOI (version native)
		
		$repository_nb_courrier = $this->getDoctrine()->getManager()->getRepository('AppBundle:EmmaEnvoi','default');
		$result_insert=$repository_nb_courrier->InsertNbCourriers($nb_courrier);
		
		return new Response($pdf2->Output('result.pdf', 'D')); //juste pour afficher

	}
	
	private function CreePDF($pdf,$recup_civ,$recup_nom, $recup_prenom, $recup_cpl_adr, $recup_adr, $recup_cp_commune,$recup_nir,$recup_cle)
	{
		$chemin=$this->container->getParameter('chemin_courrier_modele3202');
		$ficpdf_modele=$chemin."modele_courrier3202.pdf";
		$pdf->AddPage();
		$pagecount=$pdf->setSourceFile($ficpdf_modele);
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx);
		
		//Ajouter les paramètres du courrier 
		$pdf->SetFont('Arial','',10); 

		//Adresse de l'assuré
		$pdf->SetY(45);	
		$pdf->SetX(106);
		$pdf->Write(0,$recup_civ.' '.$recup_nom.' '.$recup_prenom);
		$pdf->SetY(50);
		$pdf->SetX(106);
		$pdf->Write(0,$recup_cpl_adr);		
		$pdf->SetY(55);
		$pdf->SetX(106);
		$pdf->Write(0,$recup_adr);
		$pdf->SetY(60);
		$pdf->SetX(106);
		$pdf->Write(0,$recup_cp_commune);
		
		//Numero SS
		$pdf->SetY(87);
		$pdf->SetX(31.5);
		$pdf->Write(0,$recup_nir.' '.$recup_cle);

		//Nom Prénom assuré ou ayant droit
		$pdf->SetY(96);
		$pdf->SetX(9);
		$pdf->Write(0,$recup_nom.' '.$recup_prenom);
		
		//Date du jour (en français)
		setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
		$pdf->SetY(113);
		$pdf->SetX(157);
		//$pdf->Write(0,'Le '.date('j/M/Y'));
		$pdf->Cell(40,10,'Le '.strftime('%d %B %Y'),0,0,'R');
		
		//Civilité
		$pdf->SetY(144.7);
		$pdf->SetX(61.5);
		$recup_civilite_entiere=$this->Civilite_entiere($recup_civ);
		$pdf->Write(0,$recup_civilite_entiere.',');
		
		return $pdf;
	}
	
	private function Civilite_entiere($civ){
		
		if($civ=='M'){$civ_entiere='MONSIEUR';}
		if($civ=='MME'){$civ_entiere='MADAME';}
		if($civ=='MLE'){$civ_entiere='MADEMOISELLE';}

		return $civ_entiere;
    }
}
?>