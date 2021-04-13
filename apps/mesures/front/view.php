<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class mesuresView extends WView {
	private $model;
	
	public function __construct(mesuresModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function mesures() {
		
		
		$this->assign("css","/apps/mesures-sanitaires/front/css/style.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | mesures-Covid19');
		
	}
	
}

?>