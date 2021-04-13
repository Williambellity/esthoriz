<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/admin/main.php 0007 25-08-2011 Fofif $
 */

class TresorerieAdminController extends WController {
	private $fullAccess = false;
	private $firmAccess;
	
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'liste' => "Liste des entreprises",
		//'participants' => "Participants au forum"
	);
	
	public function __construct() {
		// Chargement des modèles
		include 'model.php';
		$this->model = new EntrepriseAdminModel();
		
		include 'view.php';
		$this->setView(new EntrepriseAdminView($this->model));
		
		if (isset($_SESSION['access']['entreprise']) && $_SESSION['access']['entreprise'] == 2) {
			$action = 'participants';
		} else {
			$action = $this->getAskedAction();
			if (!isset($this->actionList[$action])) {
				$action = 'liste';
			}
		}
	}
	
	public function launch() {
		// Les notes
		WNote::treatNoteSession();
		
		$action = $this->getAskedAction();
		$this->forward($action, 'liste');
	}
	
	/**
	 * Récupération de l'id de l'utilisateur fourni en Url
	 * @param void
	 * @return int
	 */
	private function getId() {
		$args = WRoute::getArgs();
		if (empty($args[1])) {
			return -1;
		} else {
			list ($id) = explode('-', $args[1]);
			return intval($id);
		}
	}

	/**
	 * Listage des entreprises
	 */
	protected function liste() {
		// Traitement du tri
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[1]);
		$sortBy = empty($sortData) ? '' : array_shift($sortData);
		$sens = empty($sortData) ? '' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		
		// Les notes
		WNote::treatNoteSession();
		
		$filtres = WRequest::getAssoc(array('name', 'year', 'phoning_step', 'phoning_user'));
		
		$this->view->liste($sortBy, $sens, $page, $filtres);
		$this->render('liste');
	}
	
	/**
	 * Liste des participants au forum de l'année en cours
	 */
	protected function participants() {
		// Traitement du tri
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[1]);
		$sortBy = empty($sortData) ? '' : array_shift($sortData);
		$sens = empty($sortData) ? '' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		
		$this->view->participants($sortBy, $sens);
		$this->render('participants');
	}
	
	/**
	 * Algorithme pour le script ajax de recherche d'entreprises
	 */
	protected function ajax_firms() {
		$term = WRequest::get('term');
		if (!empty($term)) {
			$result = $this->model->searchFirmsByName($term, 0, 200);
			$data = "";
			$trans = "";
			foreach($result as $firm) {
				$data .= "\"".str_replace('"', '\"', $firm['name'])."\",";
				$trans .= "\"".str_replace('"', '\"', $firm['name'])."\": ".$firm['id'].",";
			}
			$data = substr($data, 0, -1);
			$trans = substr($trans, 0, -1);
			echo '{
				"data": ['.$data.'],
				"trans": {'.$trans.'}
			}';
		}
	}
	
	/**
	 * Récupération en ajax des contacts liés à une entreprise
	 */
	protected function ajax_contacts() {
		$firmid = WRequest::get('firmid');
		$this->view->contact_list($firmid);
		$this->view->tpl->display('apps/entreprise/admin/templates/contact_list.html');
	}
}
?>
