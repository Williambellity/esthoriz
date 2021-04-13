<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class actionsCovView extends WView {
	private $model;
	
	public function __construct(actionsCovModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function actionsCov() {
		
		
		$this->assign("css","/apps/actionscov/front/css/actionsCov.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | actions-Covid19');
		
	}
	
}

?>