<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class accueilView extends WView {
	private $model;
	
	public function __construct(accueilModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function accueil() {
		
		
		$this->assign("css","/apps/accueil/front/css/acceuil.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | Accueil');
		
	}
	
}

?>