<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use CNAMTS\PHPK\CoreBundle\Data\Repository;

/**
 * EmmaAssureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmmaAssureRepository extends EntityRepository implements Repository
{
	public function liste(){
		return null;
	}
	
	public function listes()
    {
		  $connexion = $this->getEntityManager()->getConnection();

		$query="select EA.NIR, PRN.DATE_DEB_PRN, PRN.DATE_FIN_PRN from EMMA_ASSURE EA INNER JOIN PRN_3202 PRN on EA.NIR = PRN.NIR";
        $statement = $connexion->query($query);
        //dump($statement);
		return $statement->fetchAll();
		
    }
	
	public function getFlux(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "select distinct flux from PRN_3202 order by flux";

        $statement = $connexion->query($sql);
        return $statement->fetchAll();
	}
	
	// Ne prend que les hommes par default
	public function getNom($civ = "M"){
		$connexion = $this->getEntityManager()->getConnection();
        $sql = "select distinct NOM from EMMA_ASSURE where CIV = '".$civ."' order by NOM";
        $statement = $connexion->query($sql);
        return $statement->fetchAll();
	}
	public function getCiv(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "select distinct CIV from EMMA_ASSURE order by CIV";

        $statement = $connexion->query($sql);
        return $statement->fetchAll();
		
	}

	public function getAssureAndPRN3202(){
		

		$connexion = $this->getEntityManager()->getConnection();

        //$sql = "select EA.NIR, EA.CLE, EA.CIV, EA.NOM, EA.PRENOM, EA.ADRESSE, EA.CPL_ADRESSE, EA.CP, EA.COMMUNE, PRN.DATE_ARR, PRN.DATE_FIN_PRN, PRN.NATPRN, PRN.SUBRO, PRN.FLUX, TRUNC(MONTHS_BETWEEN (PRN.DATE_INTEGR , PRN.DATE_ARR) ) AS MOIS_ARR, TRUNC(PRN.DATE_INTEGR - PRN.DATE_FIN_PRN)AS DAY_PRN  from EMMA_ASSURE EA INNER JOIN PRN_3202 PRN on EA.NIR = PRN.NIR WHERE PRN.DELETED IS NULL AND PRN.DATE_ENVOI IS NULL AND PRN.DATE_RECUP >= next_day(trunc(sysdate), 'MONDAY') - 14 and PRN.DATE_RECUP < next_day(trunc(sysdate), 'MONDAY') - 7";
         $sql = "select EA.NIR, EA.CLE, EA.CIV, EA.NOM, EA.PRENOM, EA.ADRESSE, EA.CPL_ADRESSE, EA.CP, EA.COMMUNE, PRN.DATE_ARR, PRN.DATE_FIN_PRN, PRN.NATPRN, PRN.SUBRO, PRN.FLUX, TRUNC(MONTHS_BETWEEN (PRN.DATE_INTEGR , PRN.DATE_ARR) ) AS MOIS_ARR, TRUNC(PRN.DATE_INTEGR - PRN.DATE_FIN_PRN)AS DAY_PRN  from EMMA_ASSURE EA INNER JOIN PRN_3202 PRN on EA.NIR = PRN.NIR WHERE PRN.DELETED IS NULL AND PRN.DATE_ENVOI IS NULL";
       
        $statement = $connexion->query($sql);
        return $statement->fetchAll();
	}

	public function getAssurefromNir($nir){
		

		$connexion = $this->getEntityManager()->getConnection();

        //$sql = "select EA.NIR, EA.CLE, EA.CIV, EA.NOM, EA.PRENOM, EA.ADRESSE, EA.CPL_ADRESSE, EA.CP, EA.COMMUNE, PRN.DATE_ARR, PRN.DATE_FIN_PRN, PRN.NATPRN, PRN.SUBRO, PRN.FLUX from EMMA_ASSURE EA INNER JOIN PRN_3202 PRN on EA.NIR = PRN.NIR WHERE DELETED IS NULL AND DATE_ENVOI IS NULL AND PRN.DATE_RECUP >= next_day(trunc(sysdate), 'MONDAY') - 14 and PRN.DATE_RECUP < next_day(trunc(sysdate), 'MONDAY') - 7 AND EA.NIR =".$nir;
        
         $sql = "select EA.NIR, EA.CLE, EA.CIV, EA.NOM, EA.PRENOM, EA.ADRESSE, EA.CPL_ADRESSE, EA.CP, EA.COMMUNE, PRN.DATE_ARR, PRN.DATE_FIN_PRN, PRN.NATPRN, PRN.SUBRO, PRN.FLUX, TRUNC(MONTHS_BETWEEN (PRN.DATE_INTEGR , PRN.DATE_ARR) ) AS MOIS_ARR, TRUNC(PRN.DATE_INTEGR - PRN.DATE_FIN_PRN)AS DAY_PRN  from EMMA_ASSURE EA INNER JOIN PRN_3202 PRN on EA.NIR = PRN.NIR WHERE PRN.DELETED IS NULL AND PRN.DATE_ENVOI IS NULL";
        $statement = $connexion->query($sql);
        
        return $statement->fetch();
	}

	public function deleteAssurefromNir($nir){
		

		$connexion = $this->getEntityManager()->getConnection();

        $sql = "UPDATE PRN_3202 SET DELETED=1 WHERE DELETED IS NULL AND DATE_ENVOI IS NULL AND NIR=".$nir;

        $statement = $connexion->query($sql);
        return $statement->execute();
	}

	public function modifAssurefromNir($nir, $adresse, $cpl_ad, $cp, $commune){
		

		$connexion = $this->getEntityManager()->getConnection();

        //$sql = "UPDATE PRN_3202 SET DATE_ARR = TO_DATE('".$date_arr."', 'DD/MM/YYYY'), SUBRO = '".$subro."', FLUX = '".$flux."' WHERE NIR='".$nir."' AND DELETED IS NULL AND DATE_ENVOI IS NULL";
		$sql = "UPDATE EMMA_ASSURE SET ADRESSE = '".$adresse."', CPL_ADRESSE = '".$cpl_ad."', CP = '".$cp."', COMMUNE = '".$commune."' WHERE NIR='".$nir."'";

        $statement = $connexion->query($sql);
        return $statement->execute();
	}

	public function popPRN(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "UPDATE PRN_3202 SET DELETED = 2 WHERE DELETED IS NULL AND DATE_ENVOI IS NULL";

        $statement = $connexion->query($sql);
        return $statement->execute();
	}

	public function pushPRN(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "UPDATE PRN_3202 SET DELETED = null WHERE DELETED = 2 AND DATE_ENVOI IS NULL";

        $statement = $connexion->query($sql);
        return $statement->execute();
	}

	public function verifSemaineDerniere(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "SELECT * from EMMA_FEND_EXTRACT where DATE_ENVOI >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_ENVOI < next_day(trunc(sysdate), 'MONDAY') - 7";

        $statement = $connexion->query($sql);
        if($statement->fetchAll()){
        	return 1;
        }else{
        	return 0;
        }
	}

	public function extraitSemaineDerniere(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "
        INSERT INTO W_PRN_3202(ETAT, ASSURE, ASSURANCE, DATE_DEB, DATE_FIN, RESULTAT, DATE_ENVOI, REINTEGR)
		SELECT ETAT, ASSURE, ASSURANCE, DATE_DEB, DATE_FIN, RESULTAT, DATE_ENVOI, 1
		FROM EMMA_FEND_EXTRACT
		WHERE DATE_ENVOI >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_ENVOI < next_day(trunc(sysdate), 'MONDAY') - 7 AND RESULTAT LIKE 'Période payée. 4%'";

        return $statement = $connexion->query($sql);
	}

	public function nettoieSemaineDerniere(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "DELETE FROM PRN_3202 
	  WHERE DATE_RECUP >= next_day(trunc(sysdate), 'MONDAY') - 14 and DATE_RECUP < next_day(trunc(sysdate), 'MONDAY') - 7";

        return $statement = $connexion->query($sql);
	}

	public function verifPremiereIntegration(){
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "SELECT * from PRN_3202 where DATE_RECUP >= next_day(trunc(sysdate), 'MONDAY') - 7 and DATE_RECUP < next_day(trunc(sysdate), 'MONDAY')";

        return $statement = $connexion->query($sql);
	}

	public function getEnvoiStats(){
		
		$connexion = $this->getEntityManager()->getConnection();

        $sql = "SELECT 
				DISTINCT TRUNC(DATE_RECUP, 'J') as DATE_ENVOI, 
				COUNT(*) as NB_ENVOI
				FROM PRN_3202 
				WHERE 
				TRUNC(DATE_RECUP, 'J') IN 
				        (SELECT DISTINCT TRUNC(DATE_RECUP, 'J') FROM PRN_3202 WHERE DATE_ENVOI IS NOT NULL OR DELETED IS NOT NULL) 
				GROUP BY TRUNC(DATE_RECUP, 'J')
				ORDER BY TRUNC(DATE_RECUP, 'J') DESC";

        $statement = $connexion->query($sql);
        return $statement->fetchAll();

	}
}