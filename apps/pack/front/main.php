<?php

class PackController extends WController {
	public function __construct() {
		include 'model.php';
		$this->model = new PackModel();
		
		// include 'view.php';
		// $this->setView(new PackView($this->model));
	}
	
	public function launch() {
		WNote::treatNoteSession();
		
		/* $this->view->assign('css', '/apps/pack/front/css/pack.css'); */
		$this->view->assign('pageTitle', "Forum Est-Horizon | Réservation");
		
		$firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId();
		if (empty($_SESSION['firmid']) && ($firmid == 0 || !isset($_SESSION['access']['all']))) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}
				
		$pack_choisi = $this->model->getPackChoisi();
		//Suppression du pack premium
		//unset(array_search(1, $pack_choisi));
		$this->view->tpl->assignBlockVars('pack_choisi', $pack_choisi);
		
		// Chargement des packs 
		foreach ($this->model->getPackList() as $pack) {
			$this->view->tpl->assignBlockVars('pack', $pack);
		}

		//$this->render('pack');
		
		$action = $this->getAskedAction();
		$this->forward($action, 'main');
	}
	
	public function main() {
		$data = WRequest::getAssoc(array('choix')); //créer une fonction pour le clic ?

		$confirmation = $this->model->getConfirmation();
        if(isset($_GET['reservation-redirect'])){
			// $pack_choisi = $this->model->getPackChoisi();
            WNote::success("Votre choix a bien été pris en compte", 'Vous pouvez le modifier à tout moment dans <br> la section \'réservation du stand\'
				de vos outils
				<form method="post" enctype="multipart/form-data" class="frm" action="http://www.est-horizon.com/entreprise/toolbox/"> 
	<input id="reg-button2" name="submit" type="submit" value="retourner au menu" style=" font-weight : bold;position : relative;
left : 400px;top :-40px;width:240px;height: 44px;" />  </form>', 'display');
			// WNote::success("Vous avez confirmé votre choix du pack".$pack_choisi['nom'].".", "Cliquez ici pour télécharger la facture au format PDF", 'session');
        }       
		elseif ($confirmation==1) {
			$pack_choisi = $this->model->getPackChoisi();
			WNote::success("Votre choix a bien été pris en compte", "Vous pouvez le modifier à tout moment dans la section \"réservation du stand\" de vos outils", 'display');	
			 
			WNote::info("Vous avez confirmé votre choix du pack".$pack_choisi['nom'].".", "Cliquez ici pour télécharger la facture au format PDF", 'session');
		}
		else {$pack_choisi = $this->model->getPackChoisi();
			if ($data['choix']!=0){
				$this->model->choisir($data);
				/*WNote::success("Votre choix a bien été pris en compte", 'Vous pouvez le modifier à tout moment dans <br> la section \'réservation du stand\'
				de vos outils
				<form method="post" enctype="multipart/form-data" class="frm" action="/bonus"> 
	<input id="reg-button2" name="submit" type="submit" value="Poursuivre ma réservation" style=" font-weight : bold;position : relative;
left : 400px;top :-40px;width:240px;height: 44px;" />  </form>', 'display');	*/
				 header('location: http://www.est-horizon.com/bonus/');	 
				//$this->render('pack');
				}
			else {
				$this->render('pack');
			}
		}
	}
}