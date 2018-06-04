<?php
namespace AppBundle\scripts;

class Fonction{
	/**
		$chemin = chemin du repertoire à supprimer
	**/
	function vider_repertoire($chemin){
		$repertoire = opendir($chemin); 
		while (false !== ($fichier = readdir($repertoire))) {
			$cheminSupp = $chemin."/".$fichier; 
			if ($fichier != ".." AND $fichier != "." AND !is_dir($fichier)){
				   unlink($cheminSupp); 
		   	}
		}
		closedir($repertoire);
	}
	
	/**
		$ftp = nom de la connection
		$repFtp = nom du repertoire à supprimer
	**/
	function vider_ftp($ftp,$repFtp){
		$contents = ftp_nlist($ftp,$repFtp);
		foreach ($contents as $content){
    		ftp_delete($ftp, $content);
		}   
	}
}
?>
