<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/view.php 0001 06-10-2011 Fofif $
 */

class VisiteursAdminView extends WView {
	private $model;
	
	public function __construct(VisiteursAdminModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Liste des entreprises
	 */
	public function liste($sortBy, $sens, $currentPage) {
		// AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('ecole', 'nom'), 'id', 'DESC');
		// Sorting vars
		$sort = $adminStyle->getSorting($sortBy, $sens);
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		$count = $this->model->countNewsletters();
		
		// Gestion de la pagination (50 firms/page)
		include HELPERS_DIR.'pagination'.DS.'pagination.php';
		$page = new Pagination($count, 50, $currentPage, '/admin/visiteurs/'.$sort[0].'-'.strtolower($sort[1]).'-%d');
		$this->assign('pagination', $page->getHtml());
		
		$newsletters = $this->model->getNewsletters(($currentPage-1)*50, 50, $sort[0], $sort[1] == 'ASC');
		$tab=array("","","","","EPITECH","CESI","EEIGM","ENGREF","ENSAIA","ENSEM","ENSG","ENSGSI","ENSIC","ESSTIN","Mines Nancy","TELECOM Nancy","ICN","ISAM-IAE","Administration des affaires","Economie appliquée","Finance, contrôle, comptabilité","Marketing & ventes","Information et communication","Sciences pour l'ingénieur et sciences des matériaux","Physique","Méthodes informatiques appliquées à la gestion des entreprises","Mécanique, énergie, Procédés et Produits","Mathématiques","Ingénierie électrique, électronique et informatique industrielle","Ingénierie des systèmes complexes","Ingénierie du développement durable","Informatique","Génie civil","Autre");
		
		foreach ($newsletters as $n) {
			$n['ecole_noms'] =$tab[$n['ecole']];
			
			$this->tpl->assignBlockVars('visiteurs', $n);
		}
	}
	
	
	
	/**
	 * Fonction de chargement de la page principale du formulaire de news
	 */
	private function loadMainForm() {
		// JS / CSS
		$this->assign('js', '/helpers/ckeditor/ckeditor.js');
		$this->assign('js', '/helpers/ckfinder/ckfinder.js');
		
		$this->assign('baseDir', WRoute::getDir());
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillMainForm($model, $data) {
		foreach ($model as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	

}

?>
