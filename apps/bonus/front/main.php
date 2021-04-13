<?php


class BonusController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new BonusModel();
		
		include 'view.php';
		$this->setView(new BonusView($this->model));
		
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
		$this->forward($action, 'bonus');
	}
	
	public function bonus() {		
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

		$this->view->bonus($pack,$ent,$contact,$factu);
		
		$this->render('bonus');
		
	}
	
}

?>
