<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class InscriptionView extends WView {
	private $model;
	
	public function __construct(InscriptionModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 *//*
	private function fillInscription($items, $data) {
		foreach ($items as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}*/
	 public function inscription() {
		if (empty($data)) {
			$data = array(0);
		}
		
		/* $this->assign("css","/apps/inscription/front/css/inscription.css"); */
		/*
	public function inscription($postData = array()) {*/
		$this->assign('pageTitle', 'Forum Est-Horizon | Inscription');
		/*$this->assign('css', '/apps/inscription/front/css/inscription.css');
		$this->assign('js', '/apps/brochure/front/js/webinscriptions2.js');*/
		
		//$data = $this->model->getInscription($firmid);
		//$this->fillInscription($data, $postData);
		
		/*$this->assign('cat_list', $this->model->getCatList());
		$this->assign('isLogo', $this->model->isLogo($firmid) ? 'true' : 'false');
		$this->assign('firmid', $firmid);*/
	}
/*
    public function pub($firmid, $postData = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Envoi de votre publicité PDF pour la brochure');
		$this->assign('css', '/apps/brochure/front/css/pub.css');
		
		$this->assign('isPub', $this->model->isPub($firmid) ? 'true' : 'false');
		$this->assign('firmid', $firmid);
	}*/
}

?>