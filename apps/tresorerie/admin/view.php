<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/admin/view.php 0002 14-05-2011 Fofif $
 */

class EntrepriseAdminView extends WView {
	private $model;
	
	public function __construct(EntrepriseAdminModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	
	/**
	 * Liste des entreprises
	 */
	public function liste($sortBy, $sens, $currentPage, $filter) {
		$this->assign('css', '/apps/entreprise/admin/css/jquery-ui-1.8.13.custom.css');
		$this->assign('css', '/apps/entreprise/admin/css/liste.css');
		$this->assign('js', '/themes/admin/javascript/jquery-ui-1.8.12.custom.min.js');
		
		// General counters
		$count_total = $this->model->countFirms();
		$this->assign('count_total', $count_total);
		$this->assign('count_contacts', $this->model->countContacts());
		
		// AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('name', 'cat_name', 'country', 'phoning_step', 'nickname', 'choix_pack', 'id'), 'name');
		// Sorting vars
		$sort = $adminStyle->getSorting($sortBy, $sens);
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		// Les utilisateurs pour le phoning
		$this->assign('users', $this->model->getPhoningUsers());
		
		// Assignation des étapes
		$steps = array('Non contactée', 'Manque ou recherche de contact', 'Entreprise phonée', 'A relancer', 'En cours d\'inscription','Inscrite', 'Finalisée', 'Refus', 'La case spéciale Lois');
		$this->assign('steps', $steps);

		// Les années pour l'inscription (départ en 2011)
		$years = array();
		for ($i = 2011; $i <= date('Y', time()); $i++) {
			$years[] = $i;
		}
		$this->assign('register_years', $years);
		
		// Var init
		$firmsPerPage = 200; // 200 firms/page
		$querystring = '';
		
		// Récupération des données avec filtrage
		if (!is_null($filter['name'])) {
			$data = $this->model->searchFirmsByName($filter['name'], ($currentPage-1)*$firmsPerPage, $firmsPerPage, $sort[0], $sort[1] == 'ASC');
			$count = $this->model->countFirmsByName($filter['name']);
			$querystring = '?name='.$filter['name'];
			$pagination_url_pattern = '/admin/tresorerie/liste/'.$sort[0].'-'.strtolower($sort[1]).'-%d/'.$querystring;
			$this->assign('filter_name', $filter['name']);
		} else if (!is_null($filter['year'])) {
			$data = $this->model->searchFirmsByYear($filter['year'], $sort[0], $sort[1] == 'ASC');
			$count = count($data);
			$querystring = '?year='.$filter['year'];
			$this->assign('filter_year_selected', $filter['year']);
		} else if (!is_null($filter['phoning_step'])) {
			$data = $this->model->searchFirmsByStep($filter['phoning_step'], ($currentPage-1)*$firmsPerPage, $firmsPerPage, $sort[0], $sort[1] == 'ASC');
			$count = $this->model->countFirmsByStep($filter['phoning_step']);
			$querystring = '?phoning_step='.$filter['phoning_step'];
			$pagination_url_pattern = '/admin/tresorerie/liste/'.$sort[0].'-'.strtolower($sort[1]).'-%d/'.$querystring;
			$this->assign('filter_phoning_step', $filter['phoning_step']);
		} else if (!is_null($filter['phoning_user'])) {
			$data = $this->model->searchFirmsByOwner($filter['phoning_user'], $sort[0], $sort[1] == 'ASC');
			$count = count($data);
			$querystring = '?phoning_user='.$filter['phoning_user'];
			$this->assign('filter_phoning_user', $filter['phoning_user']);
		}
		
		// Affichage pour la view des stats de recherche
		if (isset($count)) {
			$this->assign('count_search', $count);
		}
		
		// Default case: No filter or error encountered
		if (empty($data)) {
			$data = $this->model->getFirmList(($currentPage-1)*$firmsPerPage, $firmsPerPage, $sort[0], $sort[1] == 'ASC');
			$count = $count_total;
			$querystring = '';
			$pagination_url_pattern = '/admin/tresorerie/liste/'.$sort[0].'-'.strtolower($sort[1]).'-%d';
		}
		
		$this->assign('querystring', $querystring);
		
		// Pagination
		if (isset($pagination_url_pattern)) {
			include HELPERS_DIR.'pagination'.DS.'pagination.php';
			$pagination = new Pagination($count, $firmsPerPage, $currentPage, $pagination_url_pattern);
			$this->assign('pagination', $pagination->getHtml());
		} else {
			$this->assign('pagination', '');
		}
		
		foreach ($data as $values) {
			$values['phoning_step'] = @$steps[$values['phoning_step']];
			$this->tpl->assignBlockVars('entreprise', $values);
		}
	}
	
	public function participants($sortBy, $sens) {
		// AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('name', 'cat_name', 'country', 'date'), 'name');
		// Sorting vars
		$sort = $adminStyle->getSorting($sortBy, $sens);
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		$data = $this->model->searchFirmsByYear(date('Y', time()), $sort[0], $sort[1] == 'ASC');
		$i = 0;
		foreach ($data as $values) {
			$this->tpl->assignBlockVars('firm', $values);
			$i++;
		}
		$this->assign('nb_participants', $i);
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillForm($model, $data) {
		foreach ($model as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
}

?>
