<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/main.php 0001 06-10-2011 Fofif $
 */

class VisiteursAdminController extends WController {
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'ecoles' => "ecoles",
	);
	
	public function __construct() {
		// Chargement des modèles
		include 'model.php';
		$this->model = new VisiteursAdminModel();
		
		include 'view.php';
		$this->setView(new VisiteursAdminView($this->model));
	}
	
	public function launch() {
		// Les notes
		WNote::treatNoteSession();
		
		$action = $this->getAskedAction();
		$this->forward($action, 'liste');
	}
	
	/**
	 * Récupération de l'id de l'utilisateur fourni en Url
	 * @param void
	 * @return int
	 */
	private function getId() {
		$args = WRoute::getArgs();
		if (empty($args[1])) {
			return -1;
		} else {
			list ($id) = explode('-', $args[1]);
			return intval($id);
		}
	}
	
	/**
	 * Listage des newsletters
	 */
	protected function liste() {
		// Traitement du tri
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[0]);
		$sortBy = empty($sortData) ? 'ecole' : array_shift($sortData);
		$sens = empty($sortData) ? 'ASC' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		
		//$this->view->liste($sortBy, $sens, $page);
		$this->view->liste('ecole', $sens, $page);
		$this->render('ecoles');
	}
	
	}
	
	
	


?>
