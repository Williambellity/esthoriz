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
		
		$count = $this->model->countVisiteurs2();
		
		// Gestion de la pagination (50 firms/page)
		include HELPERS_DIR.'pagination'.DS.'pagination.php';
		$page = new Pagination($count, 50, $currentPage, '/admin/visiteurs/'.$sort[0].'-'.strtolower($sort[1]).'-%d');
		$this->assign('pagination', $page->getHtml());
		
		$visit = $this->model->getVisiteurs(($currentPage-1)*50, 50, $sort[0], $sort[1] == 'ASC');
		$ctr=array();
		$i=0;
		while($i<30)
		{
		$ctr[$i]=$this->model->countVisiteurs($i);
		$i++;
		}
		$tab=array("","","","","EPITECH","CESI","EEIGM","ENGREF","ENSAIA","ENSEM","ENSG","ENSGSI","ENSIC","ESSTIN","Mines Nancy","TELECOM Nancy","ICN","ISAM-IAE","Administration des affaires","Economie appliquée","Finance, contrôle, comptabilité","Marketing & ventes","Information et communication","Sciences pour l'ingénieur et sciences des matériaux","Physique","Méthodes informatiques appliquées à la gestion des entreprises","Mécanique, énergie, Procédés et Produits","Mathématiques","Ingénierie électrique, électronique et informatique industrielle","Ingénierie des systèmes complexes","Ingénierie du développement durable","Informatique","Génie civil","Autre");
		
		foreach ($visit as $n) {
			$n['ecole_noms'] =$tab[$n['ecole']];
			
			$this->tpl->assignBlockVars('visiteurs', $n);
		}
	}
	
	public function detail() {
	include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('ecole'), 'id', 'DESC');
		// Sorting vars
		$sort = $adminStyle->getSorting('ecole', 'DESC');
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		$count = $this->model->countVisiteurs2();
		
	include HELPERS_DIR.'pagination'.DS.'pagination.php';
		$page = new Pagination(1, 50, $currentPage, '/admin/detail/'.$sort[0].'-'.strtolower($sort[1]).'-%d');
		$this->assign('pagination', $page->getHtml());
	
		
		$ctr=array();
		$ctr2=array();
		$ctr3=array();
		$ctrP=array();
		$i=3;
		$c=1;
		while($i<=33)
		{
		$ctr[$i]=$this->model->countVisiteurs($i);
		$ctrP[$i]=$this->model->countPresents($i);
		for($c;$c<=5;$c++)
			$ctr3[$c]=$this->model->countConnu($i,$c);
		$ctr2[$i]=$ctr3;
		$c=1;
		$i++;
		}
		
		$visit = $this->model->getEcoles();
	
		
		foreach ($visit as $n) {
			$n['nombre']=$ctr[$n['id']];
			$n['connu1']= round($ctr2[$n['id']][1]/max($ctr[$n['id']],1)*100,2);
			$n['connu2']= round($ctr2[$n['id']][2]/max($ctr[$n['id']],1)*100,2);
			$n['connu3']= round($ctr2[$n['id']][3]/max($ctr[$n['id']],1)*100,2);
			$n['connu4']= round($ctr2[$n['id']][4]/max($ctr[$n['id']],1)*100,2);
			$n['connu5']= round($ctr2[$n['id']][5]/max($ctr[$n['id']],1)*100,2);
			$n['present']= round($ctrP[$n['id']]/max($ctr[$n['id']],1)*100,2);
			$this->tpl->assignBlockVars('visiteurs', $n);
		}
	}
	
	public function autres($sortBy, $sens, $currentPage) {
		// AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('ecole', 'nom'), 'id', 'DESC');
		// Sorting vars
		$sort = $adminStyle->getSorting($sortBy, $sens);
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		$count = $this->model->countVisiteursAutres();
		
		// Gestion de la pagination (50 firms/page)
		include HELPERS_DIR.'pagination'.DS.'pagination.php';
		$page = new Pagination($count, 50, $currentPage, '/admin/visiteurs/autres/autres-'.strtolower($sort[1]).'-%d');
		$this->assign('pagination', $page->getHtml());
		
		$visit = $this->model->getAutres(($currentPage-1)*50, 50,'autres', $sort[1] == 'ASC');
		
		foreach ($visit as $n) {
			
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
