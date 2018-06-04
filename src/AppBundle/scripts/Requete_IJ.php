<?php
// FUNCTION

 function getAllQuery($query, $co){
     $em=$co->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
     $em->execute();        
     $result = $em->fetch();
      return $result;
    }

function getAllQueryAll($query, $co){
     $em=$co->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
     $em->execute();        
     $result = $em->fetchAll();
      return $result;
    }

 function ExeQuery($query, $co){
      $em=$co->prepare($query,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      $em->execute();        
     
    }

 function traitement_guillemet($quote){
	   $return=str_replace("'"," ",$quote);
	   return $return;
	}
	                
 function traitement_accent($text){
	   $return = mb_convert_encoding(($text), 'UTF-8');
	   return $return;
	}

 function env_mail($dest, $nb_extraction){
		$boundary='didondinaditondelosdudosdodudundodudindon';
		//En-têtes du mail
		$headers="From: CPAM seine saint denis <noreply.cpam-bobigny@assurance-maladie.fr>\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed;boundary=\"$boundary\"\r\n\n";
		//Corps du mail en commençant par le message principal
		$body="--". $boundary ."\nContent-Type: text/plain; charset=ISO-8859-1\r\n\nVeuillez trouver ci joint le journal des événements concernant l'extraction des dossiers d'assurés par EMMA depuis les fichiers CSV mis à disposition par le FEND.\n\nNombre d'extractions réalisées : ".$nb_extraction."\r\n\n";
		
		//Fermeture de la frontière
		$body = $body . "--" . $boundary ."--";
		//Envoi du mail
		$dest ="AHMED-MOUADH.CHERAKI@cpam-bobigny.cnamts.fr,
                sylvie.vidal@assurance-maladie.fr,
                pascal.balissat@assurance-maladie.fr";
		//$dest .="@cpam-bobigny.cnamts.fr,";

        /*$dest = "dali.cpam-bobigny@assurance-maladie.fr,
        			sandrine.chagniaud@assurance-maladie.fr,
                	muriel.higonet@assurance-maladie.fr,
                	anne.clodomar@assurance-maladie.fr"*/
		mail($dest, "Extraction CSV EMMA", $body, $headers);
	}

///


$baseDistante = "
     (DESCRIPTION =
            (ADDRESS = (PROTOCOL = TCP)(HOST = miam-p-94.gstat.cnamts.fr)(PORT = 1523))
            (CONNECT_DATA =
              (SERVER = DEDICATED)
              (SERVICE_NAME = osvcusridmm94zp)
            )
      )";
$loginDistante = 'C931-BALISSAT-00752';
$passDistante  = 'BALISSAT';


$dsnDistante = "oci:dbname=$baseDistante";
try {
   $cnxDistante = new PDO($dsnDistante, $loginDistante, $passDistante);
   $cnxDistante->debug = true;
   $cnxDistante->setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


} catch (Exception $e) {
   //echo "<br/>Echec : ", $e->getMessage(), ";<br/>";
   return FALSE;
}



$baseLocal = "(DESCRIPTION =
		(ADDRESS_LIST =
		  (ADDRESS = 
		(PROTOCOL = TCP)
		(HOST = 55.166.4.95)
		(PORT = 1521))
		)
		(CONNECT_DATA =
		  (SERVER = DEDICATED)
		  (SERVICE_NAME = stat)
		)
	  )";	
$loginLocal = 'EMMA';
$passLocal  = 'EMMA';


$dsnLocal = "oci:dbname=$baseLocal";
try {
   $cnxLocal = new PDO($dsnLocal, $loginLocal, $passLocal);
   $cnxLocal->debug = true;
   $cnxLocal->setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);


} catch (Exception $e) {
   //echo "<br/>Echec : ", $e->getMessage(), ";<br/>";
   return FALSE;
}


$content = file_get_contents('Requete_IJ.sql');
$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '',$content);
$content = preg_replace('!--.*?\n!', '', $content);
$lignes = explode(";",$content);

//Suppression de la table temporaire ASSURE_OD_3202
$query_ASSURE_OD_3202="drop table ASSURE_OD_3202"; 	
ExeQuery($query_ASSURE_OD_3202,$cnxDistante);	

//Création de la table temporaire ASSURE_OD_3202
$query_ASSURE_OD_3202="create table ASSURE_OD_3202(NIR varchar2(13),DEB date,FIN date)";
ExeQuery($query_ASSURE_OD_3202,$cnxDistante);

//selection sur la table de travail qui contient les csv récupérés

/**if($_GET["reint"]){
	$query_W_PRN_3202="SELECT * from W_PRN_3202 WHERE DATE_ENVOI >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_ENVOI < next_day(trunc(sysdate), 'MONDAY') - 7 AND REINTEGR IS NOT NULL"; 
}
else{
	$query_W_PRN_3202="SELECT * from W_PRN_3202 WHERE DATE_ENVOI >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_ENVOI < next_day(trunc(sysdate), 'MONDAY') - 7"; 
}**/
$emLocal=$cnxLocal->prepare($query_W_PRN_3202,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$emLocal->execute();


$extrac =0;
while ($resultQuery_W_PRN_3202=$emLocal->fetch(PDO::FETCH_BOTH))
{

	$num_assure= substr($resultQuery_W_PRN_3202['ASSURE'],0,13);
	
	//INSERTION dans la table distante ASSURE_OD_3202 (NIR, DEb, FIN)
	$insert_ASSURE_OD_3202="INSERT INTO ASSURE_OD_3202 (NIR, DEB, FIN) values ('".$num_assure."','".$resultQuery_W_PRN_3202['DATE_DEB']."','".$resultQuery_W_PRN_3202['DATE_FIN']."') ";

	ExeQuery($insert_ASSURE_OD_3202,$cnxDistante);
	
	//Ajout dans la table distante ASSURE_OD_3202 : NOM, PRENOM, CLE, CIV
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add (NOM varchar2(50),PRENOM varchar2(50),CLE varchar2(2), CIV varchar2(3))";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	
	$query_vben_bdo="select nomstd_ben,nomprm_ben,asscle_ben, civcod_ben
	from vben_bdo
	where ASSMAC_BEN='".$num_assure."'
	and benqlt_ben='A'"; 
	$etat_assure=getAllQuery($query_vben_bdo,$cnxDistante);

	$etat_assure['NOMSTD_BEN'] = substr($etat_assure['NOMSTD_BEN'], 0, 50);
	$etat_assure['NOMPRM_BEN'] = substr($etat_assure['NOMPRM_BEN'], 0, 50);


	$update_ASSURE_OD_3202="UPDATE ASSURE_OD_3202 SET NOM='".traitement_guillemet(traitement_accent($etat_assure['NOMSTD_BEN']))."',PRENOM = '".traitement_guillemet(traitement_accent($etat_assure['NOMPRM_BEN']))."',CLE ='".$etat_assure['ASSCLE_BEN']."', CIV ='".$etat_assure['CIVCOD_BEN']."' where NIR='".$num_assure."'";
	ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	//Fin : Ajout dans la table distante ASSURE_OD_3202 : NOM, PRENOM, CLE, CIV
	
	//Ajout dans la table distante ASSURE_OD_3202 : ADRESSE
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add (ADRESSE varchar2(50),CPL_ADRESSE varchar2(50),CP varchar2(5), COMMUNE varchar2(50))";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	
	//requete pour récupérer en local l'adresse de l'assuré dans ASSURE_OD_3202
	$update_ASSURE_OD_3202="select voinum_adr,voicnu_adr,voityp_adr,voilib_adr,adrcpl_adr,bdicod_adr,rsdlib_adr
	from vadr_act
	where ASSMAC_ADR='".$num_assure."'
	 and benqlt_adr='A'";
		////echo $query2."<br>";
	$adresse_assure=getAllQuery($update_ASSURE_OD_3202,$cnxDistante);	

	$adresse_assure['ADRCPL_ADR'] = substr($adresse_assure['ADRCPL_ADR'], 0, 50);

	$adresse_complete = traitement_guillemet(traitement_accent($adresse_assure['VOINUM_ADR']))." ".traitement_guillemet(traitement_accent($adresse_assure['VOICNU_ADR']))." ".traitement_guillemet(traitement_accent($adresse_assure['VOITYP_ADR']))." ".traitement_guillemet(traitement_accent($adresse_assure['VOILIB_ADR']));
	$adresse_complete = substr($adresse_complete, 0, 50);

	$adresse_assure['RSDLIB_ADR'] = substr($adresse_assure['RSDLIB_ADR'], 0, 50);

	$update_adresse="UPDATE  ASSURE_OD_3202 SET ADRESSE='".$adresse_complete."',CPL_ADRESSE = '".traitement_guillemet(traitement_accent($adresse_assure['ADRCPL_ADR']))."',CP ='".$adresse_assure['BDICOD_ADR']."', COMMUNE ='".$adresse_assure['RSDLIB_ADR']."' where NIR='".$num_assure."'";
	////echo $update_adresse;	
	ExeQuery($update_adresse,$cnxDistante);
	//*** FIN : Ajout dans la table distante ASSURE_OD_3202 : ADRESSE
	
	
	//Création de la table distante : PRN_3202
	$query_PRN_3202="DROP TABLE PRN_3202"; 	
	ExeQuery($query_PRN_3202,$cnxDistante);	

	$query_PRN_3202="CREATE TABLE PRN_3202 as select ASSMAC_PRN MAT1,max(prndsf_prn) max_FIN_PRN
	from vprn_bdo where ASSMAC_PRN='".$num_assure."' group by ASSMAC_PRN";
	ExeQuery($query_PRN_3202,$cnxDistante);	
	
	$query_PRN_3202_037P="select '037P',count(unique MAT1),count(*)	from PRN_3202"	;
	ExeQuery($query_PRN_3202_037P,$cnxDistante);	

	$query_PRN_3202_073P="select '073P',min(max_FIN_PRN) MIN from PRN_3202";
	ExeQuery($query_PRN_3202_073P,$cnxDistante);

	$query_ASSURES_PRN_3202="DROP TABLE ASSURES_PRN_3202";
	ExeQuery($query_ASSURES_PRN_3202,$cnxDistante);	
	
	$query_ASSURES_PRN_3202="create table ASSURES_PRN_3202 as select MAT1,prnnat_prn NATURE_PRN,prndsd_prn DEB_PRN,MAX_FIN_PRN FIN_PRN from PRN_3202 ,vprn_bdo
	where MAT1=assmac_prn and MAX_FIN_PRN=prndsf_prn";	
	ExeQuery($query_ASSURES_PRN_3202,$cnxDistante);	

	$query_ASSURES_PRN_3202_037P2="select '037P',count(unique MAT1),count(*)
	from ASSURES_PRN_3202";
	ExeQuery($query_ASSURES_PRN_3202_037P2,$cnxDistante);	
	
	
	$query_ASSURES_PRN_3202="select DEB_PRN,FIN_PRN,NATURE_PRN from ASSURES_PRN_3202
where MAT1='".$num_assure."'";	

	$assure_prn=getAllQuery($query_ASSURES_PRN_3202,$cnxDistante);	
	//FIN : Création de la table distante : PRN_3202
	
	
	//Ajout dans la table distante ASSURE_OD_3202 : DEB_PRN, FIN_PRN, NAT_PRN
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add(DEB_PRN date,FIN_PRN date,NATURE_PRN varchar2(3))";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	
	
	$update_ASSURE_OD_3202="UPDATE ASSURE_OD_3202 SET DEB_PRN='".$assure_prn['DEB_PRN']."',FIN_PRN = '".$assure_prn['FIN_PRN']."', NATURE_PRN ='".$assure_prn['NATURE_PRN']."' where NIR='".$num_assure."'";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	//FIN : Ajout dans la table distante ASSURE_OD_3202 : DEB_PRN, FIN_PRN, NAT_PRN
	
	//Création de la table distante : ASSURES_IJ_3202
	$query_ASSURES_IJ_3202="DROP TABLE ASSURES_IJ_3202";
	ExeQuery($query_ASSURES_IJ_3202,$cnxDistante);	

	$query_ASSURES_IJ_3202="create table ASSURES_IJ_3202 as	select NIR MAT1,exndrd_act DEB_IJ,exndrf_act FIN_IJ,drgnat_dco DRG,entsup_dco SUP from vact,vdco,ASSURE_OD_3202 where prscat_act='IJ' and dcoref_act=dcoref_dco and assmac_act='".$num_assure."' and rgusen_dco is null and benqlt_dco='A' and exndrd_act=DEB and NIR=assmac_act group by NIR,exndrd_act, exndrf_act,drgnat_dco, entsup_dco";
	////echo $query_ASSURES_IJ_3202;
	// and (( exndrd_act - INTERVAL '1' DAY )=(DEB) OR ( exndrd_act )=(DEB))
	ExeQuery($query_ASSURES_IJ_3202,$cnxDistante);	

	$query13="select '037P',count(unique MAT1),count(*)
	from ASSURES_IJ_3202;";
	ExeQuery($query13,$cnxDistante);	
	
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add(DEB_PER date,FIN_PER date,DRG varchar2(2),SUP number,NOR varchar2(3))";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	////echo $alter_ASSURE_OD_3202;
	
	$select_ASSURE_OD_3202 = "select DEB_IJ,FIN_IJ,DRG,'NOR',SUP from ASSURES_IJ_3202 where MAT1='".$num_assure."'";
	$result_select=getAllQuery($select_ASSURE_OD_3202,$cnxDistante);	
	
	// C'est quoi NOR ?? (Nor n'existe null part)
	$update_ASSURE_OD_3202="UPDATE ASSURE_OD_3202 SET DEB_PER='".$result_select['DEB_IJ']."',FIN_PER = '".$result_select['FIN_IJ']."',DRG ='".$result_select['DRG']."', NOR ='".$result_select['NOR']."',SUP = '".$result_select['SUP']."' where NIR='".$num_assure."'";
	ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	//Suppression de la table temporaire IJ_ASSURE_3202
	$query_IJ_ASSURE_3202="drop table IJ_ASSURES_3202"; 	
	ExeQuery($query_IJ_ASSURE_3202,$cnxDistante);
	
	//Création de la table temporaire IJ_ASSURE_3202
	$query_IJ_ASSURE_3202="create table IJ_ASSURES_3202 as
	select NIR MAT,arrasu_ijp asu,perdsd_ijp deb,perdsf_ijp fin,desnum_ijp employ,ijpnbr_ijp nbrij,
	ijpmon_ijp montij,revnat_ijp rev
	from vijp_bdo,ASSURE_OD_3202
	where NIR=assmac_ijp
	and assmac_ijp = '".$num_assure."'
	group by NIR,arrasu_ijp,perdsd_ijp,perdsf_ijp,desnum_ijp,ijpnbr_ijp,ijpmon_ijp,revnat_ijp";
	ExeQuery($query_IJ_ASSURE_3202,$cnxDistante);
	////echo $query_IJ_ASSURE_3202;
	
	$query_IJ_ASSURE_3202_036A="select '036a',count(*),count(unique mat||deb||employ),count(unique mat||deb),count(unique mat)
from IJ_ASSURES_3202";
	ExeQuery($query_IJ_ASSURE_3202_036A,$cnxDistante);	
	
	$query_IJ_ARRETS_3202="drop table IJ_ARRETS_3202"; 	
	ExeQuery($query_IJ_ARRETS_3202,$cnxDistante);
	
	$query_IJ_ARRETS_3202="create table IJ_ARRETS_3202
	( employ    varchar2(14)
	 ,asu       varchar2(2)
	 ,mat       varchar(13)
	 ,deb 	    date
	 ,fin 	    date
	 ,nbrij     dec(9)
	 ,monttot   dec(9,2)
	) ";
	ExeQuery($query_IJ_ARRETS_3202,$cnxDistante);
	
	$select_IJ_ASSURES_3202="
		select mat
      	,asu
		,deb
		,fin
		,employ
		,nbrij
		,montij
		,rev
		  from IJ_ASSURES_3202
		  order by 5, 2, 1, 3, 8 desc
	";
	
	
	//$wemploy = '0';
	$wasu = 'X';
	$wmat = 'X';
	$wnbrij = 0;
	$wmonttot = 0;
	$web = "20991231";
	$wfin = "20991231";
	$wtoprec  = 0;
	
	$cij_arr1 = getAllQueryAll($select_IJ_ASSURES_3202,$cnxDistante);
	$i = 0;
	$lig = $cij_arr1[$i];
	
	$length = count(getAllQueryAll($select_IJ_ASSURES_3202,$cnxDistante));
	
	//echo"<pre>";
	//print_r(getAllQueryAll($select_IJ_ASSURES_3202,$cnxDistante));
	//echo"</pre>";
	
	//while($cij_arr1 = getAllQueryAll($select_IJ_ASSURES_3202,$cnxDistante)){
	$wemploy = 0;
	//$i = 63;
	for ($i = 0; $i<$length; $i++)
	//for ($i = 31; $i<41; $i++)
	{
		
		/**echo"<pre>";
		//echo $cij_arr1[$i]['EMPLOY']."<br>";
		//echo"</pre>";**/
		//echo "wemploy" . $wemploy ."<br>";
		//echo "cij arret".$cij_arr1[$i]['EMPLOY']."<br>";
		if($wemploy != $cij_arr1[$i]['EMPLOY'] )
		{
			//echo "employeurs " .$i."<br>";
			if($wemploy != '0')
			{	
				////echo "employeurs diff de 0 ".$i."<br>";
				//echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br> si employeur est different de 0 <br>";
				$insert_IJ_ARRETS_3202 = "insert into IJ_ARRETS_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
				ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
				//echo $insert_IJ_ARRETS_3202 ."<br>";
				//echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br>";
			}
			$wnbrij = 0;
			$wmonttot = 0;
			$wtoprev = 0;
			$wemploy = $cij_arr1[$i]['EMPLOY'];
			$wasu = $cij_arr1[$i]['ASU'];
			$wmat = $cij_arr1[$i]['MAT'];
			$wdeb = $cij_arr1[$i]['DEB'];
			$wfin = $cij_arr1[$i]['FIN'];
			//echo "wemploy apres" . $wemploy ."<br>";
			if($cij_arr1[$i]['MONTIJ'] > 0)
			{
				//echo "employeurs montant sup 0"."<br>";
				if ($cij_arr1[$i]['REV'] === null)
				{
					//echo "employeurs rev null"."<br>";
					$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
					$wtoprev = 1;
				}
				else
				{
					if ($wtoprev == 0)
					{
						//echo "employeurs to prev = 0"."<br>";
						$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
					}
				}
				$wmonttot = $wmonttot + ($cij_arr1[$i]['NBRIJ'] * $cij_arr1[$i]['MONTTOT'] );
			}
			$wdeb = $cij_arr1[$i]['DEB'];
			$wfin = $cij_arr1[$i]['FIN'];
		}
		else
		{
			//echo "assurance"."<br>";
			//echo "wasu ".$wasu."<br>";
			//echo "cij arret asu". $cij_arr1[$i]['ASU']."<br>";
			if( $wasu != $cij_arr1[$i]['ASU'])
			{
				////echo "assurance diff de asu"."<br>";
				//echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br> si si asu est difference du tableau['asu'] <br>";
				$insert_IJ_ARRETS_3202 = "insert into IJ_ARRETS_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
				ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
				//echo $insert_IJ_ARRETS_3202 ."<br>";
				//echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br>";
				

				$wnbrij = 0;
				$wmonttot = 0;
				$wtoprev = 0;
				$wmat = $cij_arr1[$i]['MAT'];
				//$wdeb = $cij_arr1[$i]['DEB'];
				//$wfin = $cij_arr1[$i]['FIN'];
				if($cij_arr1[$i]['MONTIJ'] > 0)
				{
					////echo "assurance"."<br>";
					if($cij_arr1[$i]['REV'] === null)
					{
						$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
						$wtoprev = 1;
					}
					else
					{
						if($wtoprev == 0)
						{
							$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
						}
					}
					$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
					$wdeb = $cij_arr1[$i]['DEB'];
					$wfin = $cij_arr1[$i]['FIN'];
				}
			}
				else
				{
					if($wmat != $cij_arr1[$i]['MAT'])
					{
						//echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br> INSERT DANS LE ELSE <br>";
						$insert_IJ_ARRETS_3202 = "insert into IJ_ARRETS_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
						ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
						//echo $insert_IJ_ARRETS_3202 ."<br>";
						//echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br>";
						$wnbrij = 0;
						$wmonttot = 0;
						$wtoprev = 0;
						//$wdeb = $cij_arr1[$i]['DEB'];
						//$wfin = $cij_arr1[$i]['FIN'];
						$wmat = $cij_arr1[$i]['MAT'];
						if($cij_arr1[$i]['MONTIJ'] > 0)
						{
							if( $cij_arr1[$i]['REV'] === null)
							{
								$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
								$wtoprev = 1;
							}
							else
							{
								if($wtoprev == 0){
									$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
								}
							}
						$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
						$wdeb = $cij_arr1[$i]['DEB'];
						$wfin = $cij_arr1[$i]['FIN'];
						} // if montant ij
					} // if mat
					else
					{
						////echo "else date deb ".$cij_arr1[$i]['DEB']."<br>";
						////echo "wfin ====== " . $wfin ."<br>";
						//echo "type === " . gettype($cij_arr1[$i]['DEB']). "<br>";
						
						$date_explose = explode("/", $wfin);
						$date_fin=(($date_explose[0])."-".$date_explose[1]."-20".$date_explose[2]); 
						$wfinReel = date('d/m/y', strtotime($date_fin.' + 0 DAY'));
						
						
						$date_explose2 = explode("/", $cij_arr1[$i]['DEB']);
						$date_deb=(($date_explose2[0])."-".$date_explose2[1]."-20".$date_explose2[2]); 
						
						$nouveauDeb = date('d/m/y', strtotime($date_deb.' + 0 DAY'));
						//echo "wfin reel" .$wfinReel. "<br>";
						//echo "Nouveau DEB " .$nouveauDeb. "<br>";
						
						//$date1 = new DateTime($nouveauDeb);
						//$date2 = new DateTime($cij_arr1[$i]['DEB']);
						$date = new DateTime($date_deb);
						$date1 = new DateTime($date_fin);
						//echo "NouveauDeb 1 " .gettype($date) ."<br>";
						
						if($date > $date1)
						{
							$date_explose = explode("/", $wfin);
							$date_fin=(($date_explose[0])."-".$date_explose[1]."-20".$date_explose[2]); 
                               
							// wfin1Jour = $wfin+1
							
							$wfin1Jour = date('d/m/y', strtotime($date_fin.' + 1 DAY'));
							//echo "arret DEB " .$cij_arr1[$i]['DEB']. "<br>";
							//echo "wfin +1 jour ". $wfin1Jour . "<br>";
							
							if($cij_arr1[$i]['DEB'] == $wfin1Jour) // Verifier le +1
							{
								//echo "if deb du tableau est egal a wfin +1 <br>";
								if($cij_arr1[$i]['MONTIJ'] > 0)
								{
									//echo "if montij sup 0 <br>";
									
									if($cij_arr1[$i]['REV'] === null)
								   	{
										//echo "si rev est null <br>";
										$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
										$wtoprev = 1;
									}
									else
									{
										if($wtoprev == 0)
										{
											$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
										}
									}
								$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
								}
							$wfin = $cij_arr1[$i]['FIN'];
							}
							else
							{
								//echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  <br> INSERT DANS LE ELSE DE DEB DIFFERENT DE WFIN +1 <br>";
								$insert_IJ_ARRETS_3202 = "insert into IJ_ARRETS_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
								ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
								//echo $insert_IJ_ARRETS_3202 ."<br>";
								//echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!! <br>";
								
								$wnbrij = 0;
								$wmonttot = 0;
								$wtoprev = 0;
								//$wdeb = $cij_arr1[$i]['DEB'];
								//$wfin = $cij_arr1[$i]['FIN'];
								
								if($cij_arr1[$i]['NBRIJ'] > 0)
								{
									if($cij_arr1[$i]['REV'] === null)
									{
										$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
										$wtoprev = 1;
									}
									else
									{
										if($wtoprev == 0)
										{
											$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
										}
									}
								$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
								$wdeb = $cij_arr1[$i]['DEB'];
								$wfin = $cij_arr1[$i]['FIN'];
								}
							}
						}
						else
						{
							if($cij_arr1[$i]['NBRIJ'] > 0)
							{
								if($cij_arr1[$i]['REV'] === null)
								{
									$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
									$wtoprev = 1;
								}
								else
								{
									if($wtoprev == 0)
									{
										$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
									}
								}
							$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
							}
						}
					}
				} // else montant ij
		} // else premier niveau -- if employeur
	} // end for
	//echo "<br>!!!!!!!!!!!!!!!!!!!!!!!!!!!!! <br> EN DEHORS DU FOR <br>";
	$insert_IJ_ARRETS_3202 = "insert into IJ_ARRETS_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
	ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
	//echo $insert_IJ_ARRETS_3202 ."<br>";
	//echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!! <br>";
	
	$query_IJ_ARRETS_3202_036A="'036a',count(*),count(unique mat),count(unique mat||deb),count(unique mat||deb||employ from IJ_ARRETS_3202)"	;
	ExeQuery($query_IJ_ARRETS_3202_036A,$cnxDistante);
	
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add(DEB_ARR date)";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	
	//$query_IJ_ARRETS_3202="select AR.DEB from IJ_ARRETS_3202 AR, ASSURE_OD_3202 ASS where NIR='". $num_assure."' and ASS.DEB_PER between '". $resultQuery_W_PRN_3202['DATE_DEB'] ."' and '". $resultQuery_W_PRN_3202['DATE_FIN'] ."' group by AR.DEB"; 
	//$DEB=getAllQuery($query_IJ_ARRETS_3202,$cnxDistante);	
	
	//$update_ASSURE_OD_3202 ="update ASSURE_OD_3202 set(DEB_ARR)='".$DEB['DEB']."' WHERE NIR ='".$num_assure."'";
	//ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	//echo $query_IJ_ARRETS_3202."<br>";
	
	
	// Quand on update en faisant un select, il est possible d utiliser les 2 tables sans jointures 
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 ASS set(DEB_ARR)=
	(select DEB from IJ_ARRETS_3202
	where NIR = MAT
	and DEB_PER between DEB and FIN
	group by DEB)
	where ASS.NIR='".$num_assure."'
	";
	ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add(SUBRO varchar2(5),FLUX varchar2(15))";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	
	$query_ASSURE_3202_073P="select '073P',DRG,count(*)from ASSURE_OD_3202 group by DRG order by 2";
	ExeQuery($query_ASSURE_3202_073P,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(SUBRO)='O' where DRG='S'";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(SUBRO)='N' where DRG='A'";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$query_ASSURE_3202_073P="select '073P',count(*)from ASSURE_OD_3202 where SUBRO is null";
	ExeQuery($query_ASSURE_3202_073P,$cnxDistante);
	
	$query_ASSURE_3202_073P="select '073P',SUP,count(*) from ASSURE_OD_3202 group by SUP order by 2";
	ExeQuery($query_ASSURE_3202_073P,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(FLUX)='Progres PN' where SUP=52";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(FLUX)='PE EFI' where SUP=84";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(FLUX)='PE EDI Post' where SUP=86";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(FLUX)='PE EDI M-M' where SUP=87";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$query_ASSURE_3202_073P="select '073P',count(*)from ASSURE_OD_3202 where FLUX is null";
	ExeQuery($query_ASSURE_3202_073P,$cnxDistante);
	
	$alter_ASSURE_OD_3202="alter table ASSURE_OD_3202 add(CIV2 varchar2(10))";
	ExeQuery($alter_ASSURE_OD_3202,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(CIV2)='MONSIEUR' where CIV='M'";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	
	$update_ASSURE_OD_3202="update ASSURE_OD_3202 set(CIV2)='MADAME' where CIV in('MME','MLE')";
    ExeQuery($update_ASSURE_OD_3202,$cnxDistante);
	

	
    //Début Insertion Locale
	$sel_assur="SELECT * FROM ASSURE_OD_3202 WHERE NIR=".$num_assure;
	$res = getAllQuery($sel_assur, $cnxDistante);
	
		
	$queryControl="Select NIR from EMMA_ASSURE where NIR='".$num_assure."'";
	if ($resultControl=getAllQuery($queryControl,$cnxLocal)){
		//il existe => mise à jour
		//Update EMMA_ASSURE
		$update_EMMA_ASSURE="UPDATE EMMA_ASSURE SET 
		NOM='".$res['NOM']."',
		PRENOM = '".$res['PRENOM']."',
		CLE ='".$etat_assure['ASSCLE_BEN']."', 
		CIV ='".$etat_assure['CIVCOD_BEN']."', 
		DATE_INTEGR=sysdate,
		ADRESSE='".$res['ADRESSE']."',
		CPL_ADRESSE='".$res['CPL_ADRESSE']."',
		CP='".$res['CP']."',
		COMMUNE='".$res['COMMUNE']."'
		WHERE 
		NIR='".$num_assure."'";
		ExeQuery($update_EMMA_ASSURE,$cnxLocal);
	} 
	else
	{
		//il n'existe pas => création
		//Insert EMMA_ASSURE
		$insert_EMMA_ASSURE="INSERT INTO EMMA_ASSURE 
		(NOM,PRENOM,NIR,CLE,CIV,DATE_INTEGR,ADRESSE,CPL_ADRESSE,CP,COMMUNE) 
		values 
		('".$res['NOM']."',
		'".$res['PRENOM']."',
		'".$num_assure."',
		'".$res['CLE']."',
		'".$res['CIV']."',
		sysdate,
		'".$res['ADRESSE']."',
		'".$res['CPL_ADRESSE']."',
		'".$res['CP']."',
		'".$res['COMMUNE']."') ";
		ExeQuery($insert_EMMA_ASSURE,$cnxLocal);

	}

	//Insertion dans PRN_3202 Local
	$insert_PRN_3202="INSERT INTO PRN_3202 
	(NIR,DATE_DEB_PRN,DATE_FIN_PRN,SUBRO,FLUX,DATE_RECUP,DATE_INTEGR,NATPRN,DATE_ARR) 
	values 
	('".$num_assure."',
	'".$res['DEB_PRN']."',
	'".$res['FIN_PRN']."',
	'".$res['SUBRO']."',
	'".$res['FLUX']."',
	'".$resultQuery_W_PRN_3202['DATE_ENVOI']."',
	sysdate,
	'".$res['NATURE_PRN']."',
	'".$res['DEB_ARR']."') ";
	ExeQuery($insert_PRN_3202,$cnxLocal);

	//Fin insertion Locale
	

	//Netoyage base distantes
	$query_ASSURE_OD_3202="delete from ASSURE_OD_3202"; 	
	ExeQuery($query_ASSURE_OD_3202,$cnxDistante);
	
	$query_PRN_3202="delete from PRN_3202"; 	
	ExeQuery($query_PRN_3202,$cnxDistante);	
	
	$query_ASSURES_PRN_3202="delete from ASSURES_PRN_3202"; 	
	ExeQuery($query_ASSURES_PRN_3202,$cnxDistante);	
	
	$query_ASSURES_IJ_3202="delete from ASSURES_IJ_3202";
	ExeQuery($query_ASSURES_IJ_3202,$cnxDistante);	
	
	$query_IJ_ASSURE_3202="delete from IJ_ASSURES_3202"; 	
	ExeQuery($query_IJ_ASSURE_3202,$cnxDistante);

	$query_IJ_ARRETS_3202="delete from IJ_ARRETS_3202"; 	
	ExeQuery($query_IJ_ARRETS_3202,$cnxDistante);


$extrac++;
} // end while
env_mail("", $extrac);

//Nettoyage table(s) de travail
/**if($_GET["reint"]){
	$query_W_PRN_3202="delete from W_PRN_3202 WHERE DATE_ENVOI >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_ENVOI < next_day(trunc(sysdate), 'MONDAY') - 7 AND REINTEGR IS NOT NULL"; 
}
else{
	$query_W_PRN_3202="delete from W_PRN_3202 WHERE DATE_ENVOI >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_ENVOI < next_day(trunc(sysdate), 'MONDAY') - 7"; 	
}**/
ExeQuery($query_W_PRN_3202,$cnxLocal);


?>
