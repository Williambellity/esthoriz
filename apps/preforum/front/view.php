<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/logistic/front/view.php 0000 28-04-2011 Fofif $
 */

class LogisticView extends WView {
	private $model;
	
	public function __construct(LogisticModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	public function reservation() {
		$this->assign('css', '/apps/logistic/front/css/style.css');
		
		$reducs = $this->model->getReducs();
		$this->assign('reducs', $reducs);
		
		$str = "";
		$stands = $this->model->get_all_Stands();
		$this->model->get_firm_Stand_value($stands,$str);
		$this->assign('stands', $stands['str']);
		
		$lots = $this->model->get_all_Lots();
		$this->model->get_firm_Lot_value($lots,$str);
		$this->assign('lots', $lots['str']);
		$this->assign('basics', '');
		
		$this->model->get_firm_Color_value($str);
		
		$accessoires = $this->model->get_all_Accessoires();
		$this->model->get_firm_Accessoire_value($accessoires,$str);
		$this->assign('accessoires', $accessoires['str']);
		//$this->assign('accfixes', '');
		
		$options = $this->model->get_all_Options();
		$this->model->get_firm_Option_value($options,$str);		
		$this->assign('options', $options['str']);
		$this->assign('selected', $str);
		
		$adding = $this->model->getAdding();
		if (!empty($adding[0]['enseigne'])) {
			$this->assign('enseigne', $adding[0]['enseigne']);
		} else {
			$this->assign('enseigne', $_SESSION['firmname']);
		}
		if (!empty($adding[0]['commentaire'])) {
			$this->assign('commentaire', $adding[0]['commentaire']);
		} else {
			$this->assign('commentaire', '');
		}
		/*Preparation des stands*/
		/*$stands = $this->model->get_all_Stands();
		$this->model->get_firm_Stand_value($stands);
		foreach($stands as $stand) {
			$this->tpl->assignBlockVars('stands', $stand);
		}
		$options = $this->model->get_all_Options();	
		$this->model->get_firm_Option_value($options);
		foreach($options as $option) {
			$this->tpl->assignBlockVars('options', $option);
		}
		$accessoires = $this->model->get_all_Accessoires();	
		$this->model->get_firm_Accessoire_value($accessoires);
		foreach($accessoires as $accessoire) {
			$this->tpl->assignBlockVars('accessoires', $accessoire);
		}	
		$lots = $this->model->get_all_Lots();	
		$this->model->get_firm_Lot_value($lots);
		ECHO "<pre>";
		print_r($lots);
		echo "</pre>";
		foreach($lots as $lot) {
			$this->tpl->assignBlockVars('lots', $lot);
		}*/
	}
}

?>