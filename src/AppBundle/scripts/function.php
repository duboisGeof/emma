<?php

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
	
?>
