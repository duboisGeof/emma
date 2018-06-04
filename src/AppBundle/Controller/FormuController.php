<?php
namespace AppBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\RequeteIJController;
use AppBundle\Tableaux\ListeAssure;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use CNAMTS\PHPK\CoreBundle\Generator\Form\Bouton;

class FormuController extends AbstractController
{

    public function formuAction(Request $request){
       
        $request = $this->get('request');

        $tableau = $this->get('phpk_core.tableau')->get(new ListeAssure())->setRoute('form_assure_modif');
 
        $tableau->getDataHandler()->setRepository($this->getRepository('AppBundle:EmmaAssure','default'))
            ->setRepositoryMethod('getAssureAndPRN3202');
       
        $validator = $this->get('validator');

        //Champ(s) manquants dans le tableau
        if($request->query->get('missing_error')){
            $this->notification('Champ(s) manquant(s) dans le tableau', 'error');
            return $this->render("AppBundle:Default:formu.html.twig", array('ListeAssure' => $tableau));
        }
        //Page de base
        else{
            return $this->render("AppBundle:Default:formu.html.twig", array('ListeAssure' => $tableau));
        }


    }

}