<?php
namespace AppBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use CNAMTS\PHPK\CoreBundle\Form\Type\BoutonType;
use CNAMTS\PHPK\CoreBundle\Generator\Form\Bouton;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class CSVUpload extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    /* Initialisation des données par défaut */
    $btnAnnuler = new Bouton(array('predefined' => Bouton::PREDEFINED_ABANDONNER, 'text' => 'Annuler'));
    $btnRecherche = new Bouton(array('predefined' => Bouton::PREDEFINED_VALIDER, 'text' => 'Rechercher'));

    /* Initialisation des données par défaut */
    $defaultData = array();

    /* Création du formulaire */
    $builder
                ->add('fichiers_csv', 'Symfony\Component\Form\Extension\Core\Type\FileType', array('multiple' => true,'data_class' => null))
                ->add('assures_cible', 'Symfony\Component\Form\Extension\Core\Type\TextType')
                ->add('button', 'CNAMTS\PHPK\CoreBundle\Form\Type\BoutonsType', array('attr' => array('boutons' => array($btnAnnuler, $btnRecherche))))
                ->getForm();
		
		dump($builder);
	}
	
	
}
