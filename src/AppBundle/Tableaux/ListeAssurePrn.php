<?php
namespace AppBundle\Tableaux;

use CNAMTS\PHPK\CoreBundle\Generator\Table\TableGenerator;
use CNAMTS\PHPK\CoreBundle\Generator\Table\Decorator;
use CNAMTS\PHPK\CoreBundle\Generator\Table\Cell\CellLink;

class ListeAssurePrn extends TableGenerator {

    /*
     * À surcharger obligatoirement pour y créer vos colonnes
     * Attention, ne pas oublier l'appel au contructeur parent - parent::__construct() - avant toute chose
     */
    public function __construct() {
        parent::__construct();

        $this
            ->addColumn(array(
                'id' => 'NIR',
                'name' => 'nir',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'DATE_DEB_PRN',
                'name' => 'DATE DEB',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'DATE_FIN_PRN',
                'name' => 'DATE FIN',
                'triable' => true
            ))
			
			 ->addColumn(array(
				'id' => 'detail',
                'name' => 'Details',
                'filtrable' => false,
                'decorator' => Decorator::LOUPE,
            ))
			->addColumn(array(
				'id' => 'detail',
                'name' => 'Details',
                'filtrable' => false,
                'decorator' => Decorator::LOUPE,
            ))
            /**->addColumn(array(
                'id' => 'nom',
                'name' => 'Nom',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'prenom',
                'name' => 'Prenom',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'adresse',
                'name' => 'Adresse',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'cpl_adresse',
                'name' => 'Cplt Adresse',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'cp',
                'name' => 'Code Postal',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'commune',
                'name' => 'Commune',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'date_deb_prn',
                'name' => 'Date Début PRN',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'date_fin_prn',
                'name' => 'Date Fin PRN',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'nat_assure',
                'name' => 'Civilité',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'subro',
                'name' => 'Subro',
                'triable' => true
            ))
            ->addColumn(array(
                'id' => 'flux',
                'name' => 'Flux',
                'triable' => true
            ))**/
        ;
    }

    /*
     * À surcharger obligatoirement pour remplir le tableau avec vos données
     */
    public function getRows() {
        //foreach($this->getDataHandler()->getData() as $agent) {
         foreach($this->getDataHandler()->getData() as $agent) {
            // $agent est de type CNAMTS\Mon\Bundle\Entity\Agent
            $this->addRow(array(
                    // L'ordre de remplissage des colonnes est celui configuré dans le constructeur
                    'data' => array(
                        $agent['NIR'],
                        $agent['DATE_DEB_PRN'],
                        $agent['DATE_FIN_PRN'],
                    ),
				 	'path' => array(
                    	'route' => $this->getRoute(),
                    	'param' => array('param' => $agent['NIR']),
                	),
				
                )
            );
        }
        return $this->rows;
    }
}