<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/front/view.php 0000 28-04-2011 Fofif $
 */

class ProfilView extends WView {
	private $model;
	
	public function __construct(ProfilModel $model) {
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
	
	public function profil($postData = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Edition de votre profil');
		/* $this->assign('css', '/apps/profil/front/css/profil.css'); */
		
		$data = $this->model->getContact();
		$this->fillForm($data, $postData);
	}
}

?>