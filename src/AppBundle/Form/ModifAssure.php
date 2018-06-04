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
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\DateType;


class ModifAssure extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    /* Initialisation des données par défaut */

    $test = new Bouton(array('predefined'=>Bouton::PREDEFINED_TRANSMETTRE, 'text' => 'Valider les modifications', 'id' => "modal_modif_assure"));
    /* Création du formulaire */
    $builder
                ->add('matricule', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true, 'data' => $options['data']['NIR']))
                ->add('cle', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['CLE']))
                ->add('civilite', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['CIV']))
                ->add('nom', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['NOM']))
                ->add('prenom', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['PRENOM']))
                ->add('adresse', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['ADRESSE']))
                ->add('complement_adresse', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true, 'required' => false,'data' => $options['data']['CPL_ADRESSE']))
                ->add('code_postal', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['CP']))
                ->add('commune', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['COMMUNE']));

    if(!$options['data']['DATE_ARR']){
        $builder    
                ->add('date_debut_arret', 'Symfony\Component\Form\Extension\Core\Type\DateType');
    }else{
        $builder    
                ->add('date_debut_arret', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                    'label' => 'Date Début d\'Arrêt',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'data' => new \DateTime($options['data']['DATE_ARR']),
                    'attr' => array('minDate' => '01/01/1970', 'maxDate' => '31/12/2099')));
    }
    
    $builder
                ->add('date_fin_prn', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                    'disabled' => true,
                    'label' => 'Date Fin PRN',
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy',
                    'data' => new \DateTime($options['data']['DATE_FIN_PRN']),
                    'attr' => array('minDate' => '01/01/1970', 'maxDate' => '31/12/2099')))
                ->add('nature_assure', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('disabled' => true,'data' => $options['data']['NATPRN']))
                ->add('subro', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('data' => $options['data']['SUBRO']))
                ->add('flux', 'Symfony\Component\Form\Extension\Core\Type\TextType', array('data' => $options['data']['FLUX']))
                //->add('button', 'CNAMTS\PHPK\CoreBundle\Form\Type\BoutonsType', array('attr' => array('boutons' => array($test))))
                //->setAction("valid_form_assure_modif/2630175110196")
                ->getForm();

	}
	
	
}
