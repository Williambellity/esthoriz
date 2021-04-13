<?php


class EditionentController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new EditionentModel();
		
		include 'view.php';
		$this->setView(new EditionentView($this->model));
		
	}
	
	
	public function launch() {
		WNote::treatNoteSession();
		
		$this->view->assign('css', '/apps/editionent/front/css/editionent.css');
		$this->view->assign('pageTitle', "Forum Est-Horizon | Réservation");
				
		$action = $this->getAskedAction();
		$this->forward($action, 'editionent');
	}
	
	public function editionent() {	

		$firmid = $_SESSION['firmid']; //!empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId();
		if (empty($_SESSION['firmid'])) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}	
		// Les notes
		WNote::treatNoteSession();
		
		$data = WRequest::get(array('nom', 'name', 'adress', 'postal_code', 'city', 'country','prenom','tel_fixe','email', 'adress2', 'postal_code2', 'city2', 'country2'), null, 'POST', false);
		
		 //var_dump($data);
		/*echo var_dump($_POST["nom"]);*/
		$ent=$this->model->ent();
		$this->view->tpl->assignBlockVars('ent', $ent);

		$contact=$this->model->contact();
		$this->view->tpl->assignBlockVars('contact', $contact);
		
		$factu=$this->model->factu();
		$this->view->tpl->assignBlockVars('factu', $factu);
		
		
		
		if (isset($_POST["adress2"],$_POST["city2"],$_POST["country2"],$_POST["postal_code2"])){// || !in_array(null, $data, false)) {
			$erreurs = array();
			
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'display');
			} else {
				
				if ($this->model->writeEditionent($firmid,$data)) {
					
					mb_internal_encoding('UTF-8');
					WNote::success(("Informations enregistrées"), (' Vos informations ont bien été
					prises en compte.</br>
					Les informations on été mises à jour sur votre bon de commande
					<form method="post" enctype="multipart/form-data" class="frm" action="/bonus"> 
	<input id="reg-button2" name="submit" type="submit" value="Retour à mon Bon de Commande" style=" font-weight : bold;position : relative;
left : 400px;top :-40px;width:240px;height: 44px;" />  </form>
	
	'), 'display');
	
				} else {
					WNote::error("Erreur", "Une erreur s'est produite.", 'display');
				}
			}
		} else {
			$this->view->editionent($ent,$contact,$factu);
			$this->render('editionent');
		}
		
		/*background : linear-gradient(rgb(255,255,25),rgb(255,0,25));*/
		
		
	}
	
}

?>
