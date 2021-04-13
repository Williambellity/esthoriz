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
	
	public function dashboard($sortBy, $sens, $currentPage) {
		$this->assign('css', '/apps/entreprise/admin/css/dashboard.css');
		$this->assign('css', '/apps/entreprise/admin/css/jquery-ui-1.8.13.custom.css');
		$this->assign('css', '/apps/entreprise/admin/css/liste.css');
		$this->assign('css', '/apps/entreprise/admin/css/jquery.datepick.css');
		$this->assign('js', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
		$this->assign('js', '/apps/entreprise/admin/js/jquery.plugin.js');
		$this->assign('js', '/apps/entreprise/admin/js/jquery.datepick.js');
		$this->assign('js', '/apps/entreprise/admin/js/datepicker-fr.js');
		
		
		
		// AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('name', 'cat_name', 'country', 'phoning_step', 'choix_pack', 'id', 'rappeler'), 'name');
		// Sorting vars
		$sort = $adminStyle->getSorting($sortBy, $sens);
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		$steps = array('Non contactée', 'Contactée', 'A relancer', 'Pré-inscrite','Inscrite', 'Finalisée', 'Refus');
		
		$firms = $this->model->searchFirmsByOwner2($_SESSION['userid'], $sort[0], $sort[1] == 'ASC');
		foreach ($firms as $values) {
			$values['phoning_step'] = $steps[$values['phoning_step']];
			$this->tpl->assignBlockVars('firm', $values);
		}
		
		$messages = $this->model->getShoutboxMessages();
		foreach ($messages as $m) {
			$this->tpl->assignBlockVars('shoutbox', $m);
		}
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
		$steps = array('Non contactée', 'Contactée', 'A relancer', 'Inscrite', 'Pré-inscrite', 'Finalisée', 'Refus');
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
			$pagination_url_pattern = '/admin/entreprise/liste/'.$sort[0].'-'.strtolower($sort[1]).'-%d/'.$querystring;
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
			$pagination_url_pattern = '/admin/entreprise/liste/'.$sort[0].'-'.strtolower($sort[1]).'-%d/'.$querystring;
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
			$pagination_url_pattern = '/admin/entreprise/liste/'.$sort[0].'-'.strtolower($sort[1]).'-%d';
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
	
	/**
	 * Ajout d'une entreprise
	 * plusieurs fonctions :
	 *   - données sur une entreprise
	 *   - données sur un contact
	 *   - éventuellement création d'un compte utilisateur
	 */
	public function add($data = array()) {
		$this->assign('css', '/apps/entreprise/admin/css/add.css');
		$this->assign('pays', array('France', 'Allemagne', 'Royaume-Uni', 'Espagne', 'Suisse', 'Pays-Bas', 'Autriche', 'Belgique', 'Luxembourg'));
		$this->assign('activites', $this->model->getCatList());
		$this->fillForm(
			array(
				'name' => '', 
				'identifiant' => '', 
				'cat' => '', 
				'adress' => '', 
				'city' => '', 
				'postal_code' => '', 
				'country' => '',
				'nom' => '',
				'prenom' => '', 
				'poste' => '',
				'langue' => '', 
				'adresse_contact' => '', 
				'tel_fixe' => '', 
				'tel_portable' => '', 
				'fax' => '', 
				'email' => '',
				'nickname' => ''
			),
			$data
		);
	}
	
	/**
	 * Affichage des détails concernant une entreprise
	 */
	public function fiche($id) {
		// Définition des variables de la page
		$this->assign('css', '/apps/entreprise/admin/css/fiche.css');
		$this->assign('css', '/apps/entreprise/admin/css/rating.css');
		
		$this->assign('pays', array('France', 'Allemagne', 'Autriche', 'Belgique', 'Espagne', 'Luxembourg', 'Pays-Bas', 'Royaume-Uni', 'Suisse'));
		$this->assign('activites', $this->model->getCatList());
		$this->assign('users', $this->model->getPhoningUsers());
		$this->assign('steps', array('Non contactée', 'Contactée', 'A relancer', 'Inscrite', 'Pré-inscrite', 'Finalisée', 'Refus'));
		
		// Données sur l'entreprise
		$firmData = $this->model->loadFirm($id);
		$firmData['phoning_comment'] = stripslashes($firmData['phoning_comment']);
		if (empty($firmData['postal_code'])) {
			$firmData['postal_code'] = '';
		}
		$this->assign($firmData);
		
		// L'entreprise est-elle inscrite cette année ?
		$this->assign('registered', $this->model->isRegistered($id));
		
		// Chargement des contacts liés
		$contactData = $this->model->loadFirmContacts($id);
		foreach ($contactData as $contact) {
			if ($contact['date'] == '00/00/0000 00:00') {
				$contact['date'] = '';
			}
			$contact['current_ref'] = !empty($contact['userid']) && substr($contact['date'], 6, 4) == date('Y', time());
			$this->tpl->assignBlockVars('contact', $contact);
		}
		
		// Chargement de l'historique
		$history = $this->model->loadHistory($id);
		foreach ($history as $h) {
			$this->tpl->assignBlockVars('history', $h);
		}
		
		// Les réductions
		$reducData = $this->model->loadReducs();
		$firm_reduc = $this->model->loadFirmReduc($id);
		foreach ($reducData as $reduc) {
			if (!empty($firm_reduc[$reduc['id']])) {
				$reduc['firm'] = 1;
			} else {
				$reduc['firm'] = 0;
			}
			$this->tpl->assignBlockVars('reduc', $reduc);
		}
	}
	
	public function del($id) {
		$this->assign('css', '/themes/system/styles/note.css');
		$data = $this->model->loadFirm($id);
		$this->assign('firmName', $data['name']);
	}
	
	/**
	 * Liste des contacts en attente
	 */
	public function contact_waiting() {
		$contacts = $this->model->getWaitingContacts();
		foreach ($contacts as $values) {
			$this->tpl->assignBlockVars('contact', $values);
		}
	}
	
	/**
	 * View pour ajout d'un contact
	 */
	public function contact_add($firmid, $data = array()) {
		$this->assign('css', '/apps/entreprise/admin/css/add.css');
		
		$this->assign('pays', array('France', 'Allemagne', 'Royaume-Uni', 'Espagne', 'Suisse', 'Pays-Bas', 'Autriche', 'Belgique', 'Luxembourg'));
		$this->assign('activites', $this->model->getCatList());
		
		$firmData = $this->model->loadFirm($firmid);
		$this->assign(array(
			'firmid' => $firmid,
			'firmName' => $firmData['name']
		));
		
		$this->fillForm(
			array(
				'civilite' => 'Mr',
				'nom' => '',
				'prenom' => '', 
				'poste' => '',
				'langue' => '', 
				'adresse' => '', 
				'tel_fixe' => '', 
				'tel_portable' => '', 
				'fax' => '', 
				'email' => ''
			),
			$data
		);
	}
	
	/**
	 * Edition d'un contact
	 */
	public function contact_edit($cid, $postData = array()) {
		$this->assign('css', '/apps/entreprise/admin/css/add.css');
		
		$this->assign('pays', array('France', 'Allemagne', 'Royaume-Uni', 'Espagne', 'Suisse', 'Pays-Bas', 'Autriche', 'Belgique', 'Luxembourg'));
		$this->assign('activites', $this->model->getCatList());
		
		$contactData = $this->model->loadContact($cid);
		$this->fillForm($contactData, $postData);
		
		$firmData = $this->model->loadFirm($contactData['firmid']);
		$this->assign('firmName', $firmData['name']);
	}
	
	/**
	 * View pour la suppression de contact
	 */
	public function contact_del($cid) {
		$this->assign($this->model->loadContact($cid));
	}
	
	/**
	 * View pour le traitement d'un contact
	 */
	public function contact_treat($cid, $postData = array()) {
		$this->assign('css', '/apps/entreprise/admin/css/jquery-ui-1.8.13.custom.css');
		$this->assign('css', '/apps/entreprise/admin/css/contact_treat.css');
		$this->assign('js', '/themes/admin/javascript/jquery-ui-1.8.12.custom.min.js');
		
		$cdata = $this->model->loadContact($cid);
		$this->fillForm($cdata, $postData);
		
		$this->assign('base', WRoute::getDir());
		//$this->assign('annee', date('Y', time()));
		
		// ckeditor
		$this->assign('js', '/helpers/ckeditor/ckeditor.js');
	}
	
	/**
	 * Listage des contacts liés à une entreprise
	 */
	public function contact_list($firmid) {
		$firmContacts = $this->model->loadFirmContacts($firmid);
		foreach ($firmContacts as $c) {
			$this->tpl->assignBlockVars('contact', $c);
		}
		
		$firmData = $this->model->loadFirm($firmid);
		$this->tpl->assign('firmName', $firmData['name']);
	}
	
	/**
	 * Refus d'une demande de contact
	 */
	public function contact_refuse($cid) {
		WNote::info("Avertissement", "Vous êtes sur le point de refuser une demande d'inscription au Forum faite par un contact.<br />
			Cette action entraînera <u>la suppression de toutes les données liées à ce contact</u>.<br />
			Tout récupération sera par la suite <strong>impossible</strong>.", 'assign');
		
		// ckeditor
		$this->assign('js', '/helpers/ckeditor/ckeditor.js');
		
		$this->assign('annee', date('Y', time()));
		
		$contactData = $this->model->loadContact($cid);
		$this->assign($contactData);
		
	}
	
	/**
	 * View pour les catégories
	 */
	public function cat() {
		$this->assign('css', '/apps/entreprise/admin/css/cat.css');
		$this->assign('js', '/apps/entreprise/admin/js/cat.js');
		
		$data = $this->model->getCatList();
		foreach ($data as $values) {
			$this->tpl->assignBlockVars('cat', $values);
		}
	}
}

?>
