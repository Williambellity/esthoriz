<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: apps/forum/front/main.php 0000 18-06-2011 Fofif $
 */

class ForumController extends WController {

    /*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new ForumModel();
		
		include 'view.php';
		$this->setView(new ForumView($this->model));
	}

	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'programme');
	}
	
	public function ecoles() {
		$this->view->assign('pageTitle', 'Forum Est-Horizon | Les écoles partenaires');
		$this->render('espace_etudiants');
	}
	
	public function programme() {
        $this->view->assign('css', '/apps/forum/front/css/programme.css');
		$this->render('programme');
	}
	
	public function exposants() {
        $this->view->exposants();
		$this->render('exposants');
	}

    public function fiche() {
        //$this->view->tpl->display(url du fichier);
    }
	
	public function redirect() {
		header('location: '.WRoute::getDir().'preforum/');
	}
	
	public function association() {
		$this->view->assign('pageTitle', "L'association Forum Est-Horizon");
		$this->view->assign('css', '/apps/forum/front/css/association.css');
		$this->render('association');
	}	
	
	public function partenaires() {
		$this->view->assign('pageTitle', 'Les partenaires du Forum Est-Horizon');
		$this->view->assign('css', '/apps/forum/front/css/partenaires.css');
		$this->render('partenaires');
	}
	public function presentation() {
		$this->view->assign('pageTitle', "Présentation");
		$this->render('presentation');
	}	
}
?>