<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class connexionView extends WView {
	private $model;
	
	public function __construct(connexionModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function connexion() {
		
		
		//$this->assign("css","/apps/cv/front/css/cv.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | connexion');
		
	}
	
}

?>