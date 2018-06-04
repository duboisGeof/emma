<?php
namespace AppBundle\Tableaux;

use CNAMTS\PHPK\CoreBundle\Generator\Table\TableGenerator;
use CNAMTS\PHPK\CoreBundle\Generator\Table\Decorator;
use CNAMTS\PHPK\CoreBundle\Generator\Pictogramme;
use CNAMTS\PHPK\CoreBundle\Generator\Couleur;
use CNAMTS\PHPK\CoreBundle\Generator\Table\Cell\Cell;
use CNAMTS\PHPK\CoreBundle\Generator\Table\Cell\CellPictogramme;
use CNAMTS\PHPK\CoreBundle\Generator\Table\Cell\CellModaleAjax;

class ListeAssure extends TableGenerator {

    /*
     * À surcharger obligatoirement pour y créer vos colonnes
     * Attention, ne pas oublier l'appel au contructeur parent - parent::__construct() - avant toute chose
     */
    public function __construct() {
        parent::__construct();

        $this
            ->addColumn(array(
                'id' => 'atypique',
                'name' => '',
                'triable' => true,
                'decorator' => Decorator::PICTOGRAMME
            ))
            ->addColumn(array(
                'id' => 'NIR',
                'name' => 'Matricule',
                'triable' => true, 
				'width' => 3
            ))
            ->addColumn(array(
                'id' => 'CLE',
                'name' => 'Clé',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'CIV',
                'name' => 'Civ',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'NOM',
                'name' => 'Nom',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'PRENOM',
                'name' => 'Prenom',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'ADRESSE',
                'name' => 'Adresse',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'CPL_ADRESSE',
                'name' => 'Cpt Adresse',
                'triable' => true,
            ))
            ->addColumn(array(
                'id' => 'CP',
                'name' => 'Code Postal',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'COMMUNE',
                'name' => 'Commune',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'DATE_ARR',
                'name' => 'Début Arrêt',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'DATE_FIN_PRN',
                'name' => 'Fin PRN',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'NATPRN',
                'name' => 'Nat Ass',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'SUBRO',
                'name' => 'Su bro',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'FLUX',
                'name' => 'Flux',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'modif',
                'name' => '',
                'triable' => false,
                'decorator' => Decorator::MODALE_AJAX,
            ))
            ->addColumn(array(
                'id' => 'suppr',
                'name' => '',
                'triable' => false,
                'decorator' => Decorator::PICTOGRAMME,
            ))
        ;
    }

    /*
     * À surcharger obligatoirement pour remplir le tableau avec vos données
     */
    public function getRows() {
        foreach($this->getDataHandler()->getData() as $assure) {
			
			
			// Geoffrey - 23/04/2018
			// Explode + substr de la date pour gagner de la place dans le tableau
			if(isset($assure["DATE_ARR"])){
				$dateExplodeArr = (explode(".",$assure["DATE_ARR"]));
				$jourArr = $dateExplodeArr[0];
				$moisArr = $dateExplodeArr[1];
				$anneeArr = substr($dateExplodeArr[2],2,4);
				$dateArr = $jourArr.".".$moisArr.".".$anneeArr;
			}else{
				$dateArr = $assure["DATE_ARR"];
			}
			if(isset($assure["DATE_FIN_PRN"])){
				$dateExplodePrn = (explode(".",$assure["DATE_FIN_PRN"]));
				$jourPrn = $dateExplodePrn[0];
				$moisPrn = $dateExplodePrn[1];
				$anneePrn = substr($dateExplodePrn[2],2,4);
				$datePrn = $jourPrn.".".$moisPrn.".".$anneePrn;
			}else{
				$datePrn = $assure["DATE_FIN_PRN"];
			}
			
			
			if($assure["FLUX"] ==="Progres PN"){
				$flux = "P PN";
			}elseif($assure["FLUX"] ==="PE EDI M-M"){
				$flux = "PEMM"; 
			}else{
				$flux = $assure["FLUX"];
			}
			
            // Création de la cellule avec son contenu
            $modif = new CellModaleAjax("Modification");
            $modif->setTitle('Modification de l\'Assuré');
            $modif->setCliquable($assure['NIR'] > 0);
            $modif->setRoute('form_assure_modif');
            $modif->setParam(array('nir' => $assure['NIR']));
            $modif->setPictogramme(Pictogramme::LISTE);
            //$modif->setPictogramme(Pictogramme::MODIFIER);

            /*$suppr = new CellModaleAjax("Suppression");
            $suppr->setTitle('Suppression de l\'Assuré');
            $suppr->setCliquable($assure['NIR'] > 0);
            $suppr->setRoute('form_assure_suppr');
            $suppr->setParam(array('nir' => $assure['NIR']));
            $suppr->setPictogramme(Pictogramme::CORBEILLE);*/

            $supprPicto = new CellPictogramme();
            $supprPicto->setPictogramme(Pictogramme::CORBEILLE);
            $supprPicto->setInfobulle("Supprimer l'assuré");
            $supprPicto->setId("S".$assure['NIR']);


            //$pictos = array('0' => $modif, '1' => $suppr);

            /*$piece_jointePdf = null;
            for ($i = 0; $i < 2; $i++) {
                $piece_jointePdf[$i] = new CellModaleAjax("tab_pic");
                if($i == 0){
                    $piece_jointePdf[$i]->setTitle('Modification de l\'Assuré');
                    $piece_jointePdf[$i]->setRoute('form_assure_modif');
                    $piece_jointePdf[$i]->setPictogramme(Pictogramme::LISTE);
                }
                else{
                    $piece_jointePdf[$i]->setTitle('Suppression de l\'Assuré');
                    $piece_jointePdf[$i]->setRoute('form_assure_suppr');
                    $piece_jointePdf[$i]->setPictogramme(Pictogramme::CORBEILLE);
                }
                $piece_jointePdf[$i]->setParam(array('nir' => $assure['NIR']));
                $piece_jointePdf[$i] = (array)($piece_jointePdf[$i]);
            }*/
            $atypique = new CellPictogramme();
            if(!$assure['DATE_ARR']){
                $atypique->setPictogramme(Pictogramme::PASTILLE_ROUGE);
                $atypique->setInfobulle("Date de début d'arrêt manquante");
            }else{
                if($assure['MOIS_ARR'] > 5){
                    $atypique->setPictogramme(Pictogramme::PASTILLE_ORANGE);
                    $atypique->setInfobulle("Plus de 5 mois depuis le début de l'arrêt");
                }else{
                    if($assure['DAY_PRN'] > 15){
                        $atypique->setPictogramme(Pictogramme::PASTILLE_BLEU);
                        $atypique->setInfobulle("Date de fin de PRN > 15 Jours avant la date d'intégration");
                    }else{
                        if($assure['NATPRN'] == "PRE" || $assure['NATPRN'] == "MAS"){
                            $atypique->setPictogramme(Pictogramme::PASTILLE_GRIS);
                            $atypique->setInfobulle("Nature PRE ou MAS de l'assurance");
                        }else{
                            if(strstr($assure['CPL_ADRESSE'], "*")){
                                $atypique->setPictogramme(Pictogramme::PASTILLE_NOIR);
                                $atypique->setInfobulle("Complément d'adresse contenant '*'");
                            }else{
                                $atypique->setPictogramme(Pictogramme::PASTILLE_VERT);
                                $atypique->setInfobulle("Assuré présumément valide");
                            }
                            
                        }
                    }
                }
            }
            
            //array('value' => $agent['nom'], 'options' => array(/*...*/)),

            $this->addRow(array(
					
                    // L'ordre de remplissage des colonnes est celui configuré dans le constructeur
                    'data' => array(
						
                        $atypique,
                        $assure["NIR"],
                        $assure["CLE"],
                        $assure["CIV"],
                        $assure["NOM"],
                        $assure["PRENOM"],
                        $assure["ADRESSE"],
                        $assure["CPL_ADRESSE"],
                        $assure["CP"],
                        $assure["COMMUNE"],
                        //$assure["DATE_ARR"],
						$dateArr,
                        //$assure["DATE_FIN_PRN"],
						$datePrn,
                        $assure["NATPRN"],
                        $assure["SUBRO"],
                        //$assure["FLUX"],
						$flux,
                        $modif,
                        $supprPicto
                    )
                )
            );
        }
        return $this->rows;
    }
}