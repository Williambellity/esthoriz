<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/admin/main.php 0000 25-10-2011 Fofif $
 */
//Note du 8/08/2014 on dirait qu'il manque toute la partie authentification ici
class BrochureAdminController extends WController {
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'liste' => "Liste des fiches signalétiques"
	);
	
	public function __construct() {
		// Chargement des modèles
		include 'model.php';
		$this->model = new BrochureAdminModel();
		
		include 'view.php';
		$this->setView(new BrochureAdminView($this->model));
	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'liste');
	}
	
	public function liste() {
		// Les notes
		WNote::treatNoteSession();
		
		// Traitement du tri
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[0]);
		$sortBy = empty($sortData) ? '' : array_shift($sortData);
		$sens = empty($sortData) ? '' : array_shift($sortData);
		
		$this->view->liste($sortBy, $sens);
		$this->render('liste');
	}
	
	public function bat() {
		list($bid, $state) = WRequest::get(array('id', 'state'));
		$this->model->updateTreatment($bid, $state);
		WNote::success("BAT", "La valeur d'avancement du BAT a été correctement marquée.", 'session');
		// Redirection
		header('location: '.WRoute::getDir().'admin/brochure/');
	}
}

?>
