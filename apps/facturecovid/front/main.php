<?php


class FacturecovidController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new FacturecovidModel();
		
		include 'view.php';
		$this->setView(new FacturecovidView($this->model));
		
	}
	
	
	public function launch() {
		WNote::treatNoteSession();
		
		/* $this->view->assign('css', '/apps/bonus/front/css/bonus.css'); */
		$this->view->assign('pageTitle', "Forum Est-Horizon | Réservation");
		
		//$firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId();
		if (empty($_SESSION['firmid']) && $_SESSION['nickname']!="TOKKAa" &&  $_SESSION['nickname']!="joubert" ){// && ($firmid == 0 || !isset($_SESSION['access']['all']))) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.".$_SESSION['firmid'], 'display');
			return;
		}
					
		$action = $this->getAskedAction();
		$this->forward($action, 'facturecovid');
	}
	
	public function facturecovid() {
		if(isset($_GET['reservation-redirect'])){
			// $pack_choisi = $this->model->getPackChoisi();
            WNote::success("Réservation prise en compte", 'Le bon de commande vous a été envoyé par mail. <br>N\'oubliez pas de vérifier vos spams
				<form method="post" enctype="multipart/form-data" class="frm" action="http://www.est-horizon.com/entreprise/toolbox/"> 
				<input id="reg-button2" name="submit" type="submit" value="retourner au menu" style=" font-weight : bold;position : relative;
				left : 400px;top :-40px;width:240px;height: 44px;" />  </form>', 'display');
		}
		
		// Les notes
		WNote::treatNoteSession();
		
		$data = WRequest::get(array( 'pub1', 'pub2', 'pub3', 'pub4', 'pub5','pub6','Nathan'), null, 'POST', false); 
		
		
		/*$pack=$this->model->recup();
		$this->view->tpl->assignBlockVars('pack', $pack);
		
		$ent=$this->model->ent();
		$this->view->tpl->assignBlockVars('ent', $ent);
		
		$factu=$this->model->factu();
		$this->view->tpl->assignBlockVars('factu', $factu);
		
		$contact=$this->model->contact();
		$this->view->tpl->assignBlockVars('contact', $contact);
		$contact["civilite"]=str_replace("Mr","M.",$contact["civilite"]);*/
		
		if(!empty($_POST["Nathan"]))
		{
		$pack=$this->model->recup2();
		$this->view->tpl->assignBlockVars('pack', $pack);
		
		$ent=$this->model->ent2();
		$this->view->tpl->assignBlockVars('ent', $ent);
		
		$factu=$this->model->factu2();
		$this->view->tpl->assignBlockVars('factu', $factu);
		
		$contact=$this->model->contact2();
		$this->view->tpl->assignBlockVars('contact', $contact);
		$contact["civilite"]=str_replace("Mr","M.",$contact["civilite"]);
		}
		
		
		else
		{
		$pack=$this->model->recup();
		$this->view->tpl->assignBlockVars('pack', $pack);
		
		$ent=$this->model->ent();
		$this->view->tpl->assignBlockVars('ent', $ent);
		
		$factu=$this->model->factu();
		$this->view->tpl->assignBlockVars('factu', $factu);
		
		$contact=$this->model->contact();
		$this->view->tpl->assignBlockVars('contact', $contact);
		$contact["civilite"]=str_replace("Mr","M.",$contact["civilite"]);
		}
		
		setlocale(LC_ALL, 'fr_CA.utf8'); // fr_FR pour la France

		$this->view->facturecovid($pack,$ent,$contact,$factu);
		
		$this->render('facturecovid');
		
	}
	
	// public function main() {
        // if(isset($_GET['reservation-redirect'])){
            // WNote::success("Votre choix a bien été pris en compte", 'La facture a été envoyée à votre mail. N\'oubliez pas de vérifier vos spams
				// <form method="post" enctype="multipart/form-data" class="frm" action="http://www.est-horizon.com/entreprise/toolbox/"> 
				// <input id="reg-button2" name="submit" type="submit" value="retourner au menu" style=" font-weight : bold;position : relative;
				// left : 400px;top :-40px;width:240px;height: 44px;" />  </form>', 'display');
        // }       
		
	// }
		
}
?>