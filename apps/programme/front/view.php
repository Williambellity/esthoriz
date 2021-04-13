<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class espace_etudiantsView extends WView {
	private $model;
	
	public function __construct(espace_etudiantsModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function espace_etudiants() {
		
		
		//$this->assign("css","/apps/cv/front/css/cv.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | Programme-Animation');
		$this->assign("css","/apps/espace-etudiants/front/css/programme.css");
		
	}
	
}

?>