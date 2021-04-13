<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class seekubeView extends WView {
	private $model;
	
	public function __construct(seekubeModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function seekube() {
		
		
		$this->assign("css","/apps/seekube/front/css/style.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | seekube-Covid19');
		
	}
	
}

?>