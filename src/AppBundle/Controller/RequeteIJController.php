<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class RequeteIJController extends AbstractController
{

    public function getAllQuery($query, $em){
        $statement = $em->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function modifyQuery($query, $em){
        $statement = $em->getConnection()->prepare($query);
        $statement->execute();
        /*if($result)return true;
        return false;*/
    }

    public function traitement_guillemet($quote){
        return str_replace("'"," ",$quote);
    }

    public function genererAction($importfichier, $emD, $emC, $bdd){

        //$this->modifyQuery("DELETE FROM W_PRN_3202", $emD);
        $i = 0;
        for($i = 0; $i < count($importfichier); $i++){
            $this->modifyQuery("
                INSERT INTO ".$bdd."(ETAT, ASSURE, ASSURANCE, DATE_DEB, DATE_FIN, RESULTAT, DATE_ENVOI)
                VALUES (
                    '".$importfichier[$i][0]."',
                    '".$importfichier[$i][1]."',
                    '".$importfichier[$i][2]."',
                    '".$importfichier[$i][3]."',
                    '".$importfichier[$i][4]."',
                    '".$this->traitement_guillemet($importfichier[$i][5])."',
                    sysdate)
                ", $emD);
        }
		
        return $i;

    }

}