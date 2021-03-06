$(document).ready(function() {
	var oInput = document.getElementById('choose_logo'),
		oDropArea = document.body,
		oLine = document.getElementById('progressBar');
		
	function displayLogo(b) {
		if(b) {
			var d = new Date();
			var url = "/upload/firms_logo/"+firmid+".png?ref="+d.getUTCMilliseconds();
			//$('#td_logo').css('backgroundImage',url);
			$('#td_logo>img').attr('src',url);
		} else {
			//$('#td_logo').css('backgroundImage','url(/apps/brochure/front/images/nologo.jpg)');
			$('#td_logo>img').attr('src',"/apps/brochure/front/images/nologo.jpg");
		}
	}
	
	function start() {
		if ($('#website').val()=="") {
			$('#website').val("http://");
		}
	}
	
	start();
	
	function progressOrNot(b) {
		if(b) {
			$('#ch_logo').hide();
			$('#progressBar').show(0,function() {
				displayLogo(false);
			});
		} else {
			$('#progressBar').fadeOut(1000,function () {
				displayLogo(true);
				$('#ch_logo').fadeIn(400);
			});
		}
	}
	
	displayLogo(isLogo);
	
	if(window.XMLHttpRequest) {
	
		$('input#parcourir').click( function() {
			$('input#choose_logo').show(0,function () {
				$('input#choose_logo').click();
			});
			$('input#choose_logo').hide(0);
		});
		
		/*$('#main_infos').hover( 
			function() {
				if($('#progressBar').is(':hidden')) {
					$('#ch_logo').fadeIn(500);}
			} ,function() {
				$('#ch_logo').fadeOut(500);
			});*/
			
		oInput.onchange = function(e) {
			console.log("oInput.onchange");
			XHR2Uploader.addNewFiles(this.files);
		};
		
		function bindEvent(el, eventName, eventHandler) {
		  if (el.addEventListener){
			el.addEventListener(eventName, eventHandler, false); 
		  } else if (el.attachEvent){
			el.attachEvent('on'+eventName, eventHandler);
		  }
		}
		
		bindEvent(oDropArea, 'dragover', function(e) {
			if (!e) {
				e = event;
			}
			e.dataTransfer.effectAllowed = 'copy';
			e.dataTransfer.dropEffect = 'copy';
			e.preventDefault();
		});
		
		bindEvent(oDropArea, 'drop', function(e) {
			if (!e) {
				e = event;
			}
			e.preventDefault();
			if('files' in e.dataTransfer) {
				XHR2Uploader.addNewFiles(e.dataTransfer.files);
			} else {
				$('#message_drop').html('Navigateur icapable');
			}
		});
		/*
		oDropArea.ondragover = function(e) {
			if (!e) {
				e = event;
			}
			e.dataTransfer.effectAllowed = "copy";
			e.dataTransfer.dropEffect = "copy";
			//e.preventDefault();
		};
		
		oDropArea.ondrop = function(e) {
			if (!e) {
				e = event;
			}
			//e.preventDefault();
			if('files' in e.dataTransfer) {
				XHR2Uploader.addNewFiles(e.dataTransfer.files);
			} else {
				$('#message_drop').html('Navigateur icapable');
			}
		};*/
		
		var XHR2Uploader = {
			aQueue:[], // contient la liste des objets File ????  envoyer
			oXHR:window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"),
			oCurrentFile:null, // pointeur utile vers le fichier ????  envoyer
			addNewFiles: function(aFiles) {
				// ici on acc????????de ????  la liste des fichiers s????????lectionn????????s (FileList)
				var oFile;
				oFile = aFiles[0]; // r????????f????????rence locale
				// ajout ????  la liste des fichiers ????  traiter
				XHR2Uploader.aQueue.push(oFile);
				// liste des fichiers mis ????  jour dans l'interface
				// ceci nous servira ????  identifier la ligne correspondant au fichier
				$('#progressBar').attr('max',oFile.size);
				$('#progressBar').attr('value',0);
				progressOrNot(true);
				// si la requ????????te n'a jamais ????????t???????? lanc????????e ou si la requ????????te pr????????c????????dente est termin????????e
				if(XHR2Uploader.oXHR.readyState === 0
				|| XHR2Uploader.oXHR.readyState === 4) {
					XHR2Uploader.startUpload();
				}
				//console.log('addNewFiles', XHR2Uploader.oXHR.readyState);
			},
			// ????  appeller lorsque aucun envoi n'est en cours
			startUpload:function() {
			if(XHR2Uploader.aQueue.length < 1) // rien ????  envoyer ?
				return;
			XHR2Uploader.oXHR.open('POST', '/brochure/uploadLogo/'+firmid);
			// le constructeur de FormData se trouve dans le scope global
			var oFormData = new FormData( );
			XHR2Uploader.oCurrentFile = XHR2Uploader.aQueue.shift();// pointeur vers le premier fichier de la queue
			// on construit l'????????quivalent du formulaire HTML
			oFormData.append(
				oInput.name, // r????????cup????????ration du nom du champs
				XHR2Uploader.oCurrentFile
			);
				// XHR2.send va formatter une requ????????te POST classique ????  partir de cet objet
				XHR2Uploader.oXHR.send( oFormData );
			},
			// appell????????e pendant l???????????????envoi : met ????  jour les barres de progression
			onUploading:function(e) {
				// on r????????cup????????re la barre de progression associ????????e ????  notre fichier
				$('#progressBar').attr('value',e.loaded);
			},
			// appell????????e ????  la fin de l???????????????envoi : mise ????  jour de l???????????????interface, poursuit la queue d???????????????envoi
			onUploaded:function(e) {
				//console.log('onUploaded');
				$('#progressBar').attr('value',XHR2Uploader.oCurrentFile.size);
				XHR2Uploader.startUpload();
				displayLogo(true);
				progressOrNot(false);
			}
		};
		
		if (window.XMLHttpRequest) {
			if (XHR2Uploader.oXHR.addEventListener){
				XHR2Uploader.oXHR.addEventListener('load', XHR2Uploader.onUploaded, true);
				XHR2Uploader.oXHR.upload.addEventListener('progress', XHR2Uploader.onUploading, true);
			} else if (XHR2Uploader.oXHR.attachEvent){
				XHR2Uploader.oXHR.attachEvent('onload', XHR2Uploader.onUploaded);
				XHR2Uploader.oXHR.attachEvent('onprogress', XHR2Uploader.onUploading);
			}
			/*
			alert(typeof XHR2Uploader.oXHR);
			XHR2Uploader.oXHR.addEventListener('load', XHR2Uploader.onUploaded, true);
			//XHR2Uploader.oXHR.upload.addEventListener('progress', XHR2Uploader.onUploading, true);
			XHR2Uploader.oXHR.addEventListener('progress', XHR2Uploader.onUploading, true);*/
		}
	} else {
		$('#parcourir').hide(0);
		$('#message_drop').hide(0);
		$('#message_alt').show(0);
		$('#choose_logo').show(0);
		$('#maudit_ie6').attr('checked','checked');
	}
	
	//window.XMLHttpRequest ? XHR2Uploader.oXHR.addEventListener('load', XHR2Uploader.onUploaded, true):function () {};
	//window.XMLHttpRequest ? XHR2Uploader.oXHR.upload.addEventListener('progress', XHR2Uploader.onUploading, true):function () {};
	//console.log(oXHR.readyState); 
});