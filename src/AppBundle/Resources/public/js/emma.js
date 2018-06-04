jQuery(document).ready(function($) {
    
});



window.addEvent('load', function(event) {
   $$('#phpk_modale_ajax').addEvent('click:relay(#modal_modif_assure)' , function(event, params){

            var nir = $$('#modif_assure_matricule').get('value')
            var deb_arr = $$('#modif_assure_date_debut_arret').get('value')
            var subro = $$('#modif_assure_subro').get('value')
            var flux = $$('#modif_assure_flux').get('value')

            if(deb_arr[0] === "" || subro[0] === ""|| flux[0] === ""){
                alert("Champ(s) vide(s)")
            }else{
                var request = new Request.HTML({
                    method: 'post',
                    evalScripts: true,
                    evalResponse: true,
                    url: Routing.generate('valid_form_assure_modifAjax'),
                    data: 'nir='+nir+'&deb_arr='+deb_arr+'&subro='+subro+'&flux='+flux,
                    //update: $('phpk_modale_ajax_contenu'),
                    onRequest: function () {
                       //alert ('request')
                    },
                    onSuccess: function(responseText){
                        //alert("Success")
                        returnValue=(responseText[0].data);
                        //alert (returnValue)
                        if(returnValue == "true"){
                            $$('#phpk_modale_ajax').addClass('invisible')
                            //var tmp = window.location.href.split("?")[0]+"?modif";
                            if( window.location.href.indexOf("?") == -1){
                                window.location.reload(true);
                            }else{
                                var tmp = window.location.href.split("?")[0];
                                window.location = tmp
                            }
                        }else{
                            alert("Erreur d'insertion")
                        }
                     },

                    onComplete: function (response) {
                        //alert ('complete')
                        console.log("complete")
                    },
                    onFailure: function (response) {
                       //alert ('fail')
                    }
                }).send();
            }
            
    });

    $$('i.infobulle').addEvent('click', function () {
        var id = this.getParent('.phpk_decorator_pictogramme').get('id').substring(1);
        var msg;
        var confirmed = confirm("Confirmer la suppression");

        if(confirmed){
            var request = new Request.HTML({
                method: 'post',
                evalScripts: true,
                evalResponse: true,
                url: Routing.generate('valid_form_assure_supprAjax'),
                data: 'nir='+id,
                //update: $('phpk_modale_ajax_contenu'),
                onRequest: function () {
                   //alert ('request')
                },
                onSuccess: function(responseText){
                    //alert("Success")
                    returnValue=(responseText[0].data);
                    //alert (returnValue)
                    if(returnValue == "true"){
                        if( window.location.href.indexOf("?") == -1){
                            window.location.reload(true);
                        }else{
                            var tmp = window.location.href.split("?")[0];
                            window.location = tmp
                        }
                    }else{
                        alert("Erreur d'insertion")
                    }
                 },
                onComplete: function (response) {
                    //alert ('complete')
                    console.log("complete")
                },
                onFailure: function (response) {
                   //alert ('fail')
                }
            }).send();
        }
    });

    //Loaders page Gestion des CSV
    $$('#csv_envoi').addEvent('click', function () {
    	setTimeout(function(){
			if($$('#csv_upload_fichiers_csv').get('value') !== ""){
	    		$$('body').appendHTML('<div id="phpk_modale_ajax_overlay" class="popbg" style="z-index: 100;"></div>', 'top');
		    	$$('#csv_envoi').set('disabled',true);
		    	$$('#phpk_modale_ajax').removeClass('invisible');
		    	$$('#phpk_modale_ajax').setStyle('z-index', 101);
	    	}
    	}, 300);
    });

    $$('#suppr_csv_envoi').addEvent('click', function () {
    	setTimeout(function(){
	    	if($$('#csv_gestion_fend_date_envoi_chzn_text').get('value') !== undefined){
	    		$$('body').appendHTML('<div id="phpk_modale_ajax_overlay" class="popbg" style="z-index: 100;"></div>', 'top');
		    	$$('#suppr_csv_envoi').set('disabled',true);
		    	$$('#phpk_modale_ajax').removeClass('invisible');
		    	$$('#phpk_modale_ajax').setStyle('z-index', 101);
	    	}
    	}, 300);
    });

    //Loaders page Gestion des courriers assurés
    $$('#recharger_integration').addEvent('click', function () {
    	setTimeout(function(){
			$$('body').appendHTML('<div id="phpk_modale_ajax_overlay" class="popbg" style="z-index: 100;"></div>', 'top');
	    	$$('#recharger_integration').set('disabled',true);
	    	$$('#phpk_modale_ajax').removeClass('invisible');
	    	$$('#phpk_modale_ajax').setStyle('z-index', 101);
    	}, 300);
    });

    $$('#phpk_modale_ajax').addEvent('click:relay(button#phpk_confirm_button.bouton)' , function(event, params){
        console.log("click");
    });
});