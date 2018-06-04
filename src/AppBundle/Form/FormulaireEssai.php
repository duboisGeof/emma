<?php
namespace AppBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use CNAMTS\PHPK\CoreBundle\Form\Type\BoutonType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use CNAMTS\PHPK\CoreBundle\Generator\Form\Bouton;


use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormulaireEssai extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
		//print_r($options['data']);
     //dump($options);
    /* Initialisation des données par défaut */
    $defaultData = array();

    /* Création du formulaire */
    $builder
			->add('ident', 'text', array('required' => true,
                                        'attr' => array(
											'size' =>9,
											'class' => "idTest"
										),
										'label' => 'Identification',
            ))
            ->add('Cle', 'number', array(
                                        'required' => true,
										'label' => 'Clé',
										'attr' => array('size' => 1),
                                        'mapped' => false
            ))
			
			->add('Flux','choice', array(
					'label'=>'Flux',
					'choices' => $options['data']['FLUX'],
				))
		   
		    ->add('Civ','choice', array(
					'label'=>'Civilité',
					'choices' => $options['data']['CIV'],
				))
		
			->add('status','choice', array(
				'choice_list' => new ChoiceList(
					array(1, 0.5, 0.1),
					array('Full', 'Half', 'Almost empty')
				)))
		
			->add('Nom','choice', array(
					'label'=>'Nom',
					'choices' => $options['data']['NOM'],
				))
			->setAction('configuration')
			->setMethod("POST")
			->getForm();

			

		/* Création des boutons */
		$btnAnnuler = new Bouton(array('predefined' => Bouton::PREDEFINED_RETABLIR, 'text' => 'Annuler', 'id'=>'idBtnAnnuler'));
		$valider = new Bouton(array('predefined'=>Bouton::PREDEFINED_VALIDER, 'text' => 'Valider', 'id'=>'idBtnValider'));
		$test = new Bouton(array('predefined'=>Bouton::PREDEFINED_TRANSMETTRE, 'text' => 'Test', 'id'=>'idBtnTest'));

        $builder->add('boutons', 'CNAMTS\PHPK\CoreBundle\Form\Type\BoutonsType', array('attr' => array('boutons' => array($btnAnnuler,$valider, $test))));
		
		//dump($builder);
	}
	
	
}
