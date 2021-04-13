<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/cvtheque/admin/view.php 0000 11-11-2011 Fofif $
 */

class CvthequeAdminView extends WView {
	private $model;
	
	public function __construct(CvthequeAdminModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillForm($model, $data) {
		foreach ($model as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
	/**
	 * View pour l'ajout d'un créneau
	 */
	public function addTimeSlot() {
		$this->assign('css', '/apps/entreprise/admin/css/jquery-ui-1.8.13.custom.css');
		$this->assign('js', '/themes/admin/javascript/jquery-ui-1.8.12.custom.min.js');
		
		// $this->fillForm($cdata, $postData);
		
		$this->assign('base', WRoute::getDir());
	}
}

?>
