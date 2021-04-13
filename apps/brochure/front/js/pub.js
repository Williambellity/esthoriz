$(document).ready(function() {
	var oInput = document.getElementById('choose_pub'),
		oDropArea = document.body,
		oLine = document.getElementById('progressBar');
		
	function displayPub(b) {
		if(b) {
			var d = new Date();
			var url = "/upload/pubBrochure/2011/"+firmid+".jpg?ref="+d.getUTCMilliseconds();
			//$('#td_logo').css('backgroundImage',url);
			$('#td_pub>img').attr('src',url);
		} else {
			//$('#td_logo').css('backgroundImage','url(/apps/brochure/front/images/nologo.jpg)');
			$('#td_pub>img').attr('src',"/apps/brochure/front/images/nopub.png");
		}
	}
	
	function progressOrNot(b) {
		if(b) {
			$('#ch_pub').hide();
			$('#progressBar').show(0,function() {
				displayPub(false);
			});
		} else {
			$('#progressBar').fadeOut(1000,function () {
				displayPub(true);
				$('#ch_pub').fadeIn(400);
			});
		}
	}
	
	displayPub(isPub);
	
	if(window.XMLHttpRequest) {
	
		$('input#parcourir').click( function() {
			$('input#choose_pub').show(0,function () {
				$('input#choose_pub').click();
			});
			$('input#choose_pub').hide(0);
		});
			
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
		
		var XHR2Uploader = {
			aQueue:[], // contient la liste des objets File Ãƒ  envoyer
			oXHR:window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"),
			oCurrentFile:null, // pointeur utile vers le fichier Ãƒ  envoyer
			addNewFiles: function(aFiles) {
				// ici on accÃƒÂ¨de Ãƒ  la liste des fichiers sÃƒÂ©lectionnÃƒÂ©s (FileList)
				var oFile;
				oFile = aFiles[0]; // rÃƒÂ©fÃƒÂ©rence locale
				// ajout Ãƒ  la liste des fichiers Ãƒ  traiter
				XHR2Uploader.aQueue.push(oFile);
				// liste des fichiers mis Ãƒ  jour dans l'interface
				// ceci nous servira Ãƒ  identifier la ligne correspondant au fichier
				$('#progressBar').attr('max',oFile.size);
				$('#progressBar').attr('value',0);
				progressOrNot(true);
				// si la requÃƒÂªte n'a jamais ÃƒÂ©tÃƒÂ© lancÃƒÂ©e ou si la requÃƒÂªte prÃƒÂ©cÃƒÂ©dente est terminÃƒÂ©e
				if(XHR2Uploader.oXHR.readyState === 0
				|| XHR2Uploader.oXHR.readyState === 4) {
					XHR2Uploader.startUpload();
				}
				//console.log('addNewFiles', XHR2Uploader.oXHR.readyState);
			},
			// Ãƒ  appeller lorsque aucun envoi n'est en cours
			startUpload:function() {
			if(XHR2Uploader.aQueue.length < 1) // rien Ãƒ  envoyer ?
				return;
			XHR2Uploader.oXHR.open('POST', '/brochure/uploadBrochure/'+firmid);
			// le constructeur de FormData se trouve dans le scope global
			var oFormData = new FormData( );
			XHR2Uploader.oCurrentFile = XHR2Uploader.aQueue.shift();// pointeur vers le premier fichier de la queue
			// on construit l'ÃƒÂ©quivalent du formulaire HTML
			oFormData.append(
				oInput.name, // rÃƒÂ©cupÃƒÂ©ration du nom du champs
				XHR2Uploader.oCurrentFile
			);
				// XHR2.send va formatter une requÃƒÂªte POST classique Ãƒ  partir de cet objet
				XHR2Uploader.oXHR.send( oFormData );
			},
			// appellÃƒÂ©e pendant lÃ¢â‚¬Ëœenvoi : met Ãƒ  jour les barres de progression
			onUploading:function(e) {
				// on rÃƒÂ©cupÃƒÂ¨re la barre de progression associÃƒÂ©e Ãƒ  notre fichier
				$('#progressBar').attr('value',e.loaded);
			},
			// appellÃƒÂ©e Ãƒ  la fin de lÃ¢â‚¬Ëœenvoi : mise Ãƒ  jour de lÃ¢â‚¬Ëœinterface, poursuit la queue dÃ¢â‚¬Ëœenvoi
			onUploaded:function(e) {
				//console.log('onUploaded');
				$('#progressBar').attr('value',XHR2Uploader.oCurrentFile.size);
				XHR2Uploader.startUpload();
				displayPub(true);
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
		$('#choose_pub').show(0);
		$('#maudit_ie6').attr('checked','checked');
	}
	
	//window.XMLHttpRequest ? XHR2Uploader.oXHR.addEventListener('load', XHR2Uploader.onUploaded, true):function () {};
	//window.XMLHttpRequest ? XHR2Uploader.oXHR.upload.addEventListener('progress', XHR2Uploader.onUploading, true):function () {};
	//console.log(oXHR.readyState); 
});