<?php
/**
 * Wity CMS
 * Syst�me de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class cvView extends WView {
	private $model;
	
	public function __construct(cvModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	

	 public function cv() {
		
		
		$this->assign("css","/apps/cv/front/css/cv.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | cv');
		
	}
	
	public function cvd() {
		
		
		$this->assign("css","/apps/cv/front/css/cv.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | cv');
		
	}
	
	public function cvl() {
		
		
		$this->assign("css","/apps/cv/front/css/cv.css");
		/*
	public function form($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | cv');
		
	}
}

?>