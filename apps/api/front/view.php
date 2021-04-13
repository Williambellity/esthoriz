<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class ApiView extends WView {
	private $model;
	
	public function __construct(ApiModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillForm($items, $data) {
		foreach ($items as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
	public function brochure($firmid, $postData = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Saisie de votre fiche signalétique');
		/* $this->assign('css', '/apps/brochure/front/css/brochure.css'); */
		/* $this->assign('js', '/apps/brochure/front/js/webforms2.js'); */
		
		$data = $this->model->getApi($firmid);
		$this->fillForm($data, $postData);
		
		$this->assign('cat_list', $this->model->getCatList());
		$this->assign('isLogo', $this->model->isLogo($firmid) ? 'true' : 'false');
		$this->assign('firmid', $firmid);
	}

    public function pub($firmid, $postData = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Envoi de votre publicité PDF pour la brochure');
		$this->assign('css', '/apps/brochure/front/css/pub.css');
		
		$this->assign('isPub', $this->model->isPub($firmid) ? 'true' : 'false');
		$this->assign('firmid', $firmid);
	}
}

?>