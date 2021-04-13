<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/admin/view.php 0000 25-10-2011 Fofif $
 */

class BrochureAdminView extends WView {
	private $model;
	
	public function __construct(BrochureAdminModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Liste des fiches signalétiques
	 */
	public function liste($sortBy, $sens) {
		// AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('name', 'date'), 'name');
		// Sorting vars
		$sort = $adminStyle->getSorting($sortBy, $sens);
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		$data = $this->model->getBrochure($sort[0], $sort[1] == 'ASC');
		$count = 0;
		foreach ($data as $values) {
			$values['pub'] = substr_count($values['options'], '14/') > 0;
			$values['logo'] = $this->model->isLogo($values['firmid']);
			$this->tpl->assignBlockVars('brochure', $values);
			$count++;
		}
		$this->assign('count', $count);
	}
}

?>
