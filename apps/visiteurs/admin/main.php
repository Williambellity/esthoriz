<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/main.php 0001 06-10-2011 Fofif $
 */

class VisiteursAdminController extends WController {
	/*
	 * Les opérations du module
	 */
	protected $authorizedUsers = array(600);
	 
	protected $actionList = array(
		'ecoles' => "ecoles",
		
		'autres' => "Les Autres",
		
		'mailnonvalide' => "Mails non validés",
		
		'excel_print' => "Imprimer la liste des inscrits"
	);
	
	public function __construct() {
	 if (in_array($_SESSION['userid'], $this->authorizedUsers)) {
	// $this->actionList['ecoles'] = "ecoles";
	$this->actionList['detail'] = "detail";
	//$this->actionList['autres'] = "Les Autres";
	 
	 }
		// Chargement des modèles
		include 'model.php';
		$this->model = new VisiteursAdminModel();
		
		include 'view.php';
		$this->setView(new VisiteursAdminView($this->model));
		
		
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
	 * Listage des newsletters
	 */
	protected function liste() {
		// Traitement du tri
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[0]);
		$sortBy = empty($sortData) ? 'ecole' : array_shift($sortData);
		$sens = empty($sortData) ? 'ASC' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		
		//$this->view->liste($sortBy, $sens, $page);
		$this->view->liste('ecole', $sens, $page);
		$this->render('ecoles');
	}
	
	protected function detail() {
	if (in_array($_SESSION['userid'], $this->authorizedUsers)) {
		$this->view->detail();
		$this->render('detail');}
	else{$this->view->liste('ecole', 'ASC', 1);
		$this->render('ecoles');}
	
	}
	
	protected function autres(){
	$args = WRoute::getArgs();
		$sortData = explode('-', @$args[1]);
		$sortBy = empty($sortData) ? 'autres' : array_shift($sortData);
		$sens = empty($sortData) ? 'ASC' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		$this->view->autres('autres', $sens, $page);
		$this->render('Les Autres');
	}
	
	protected function mailnonvalide(){
	$args = WRoute::getArgs();
		$sortData = explode('-', @$args[0]);
		$sortBy = empty($sortData) ? 'autres' : array_shift($sortData);
		$sens = empty($sortData) ? 'ASC' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		$this->view->mailnonvalide('ecole', $sens, $page);
		$this->render('Mails non validés');
	}	
	
	public function excel_print(){
		//recuperer la liste des personnes
		if (isset($_SESSION['access']['visiteurs']) || in_array('all', $_SESSION['access'])) {
			$data = $this->model->listPersons();
		}
		else {
			$data = array(array());
		}
 		//impression dans excel
		require('helpers/excelPHP/PHPExcel.php');
		$excel = new PHPExcel();
		//Writing 
		$head = array('Nom','Prénom','Email','Ecole ID','Ecole','Droit_Mail','Droit_Photo',
						'Connu1','Connu2','Connu3','Connu4','Connu5','Connu6',"Mail validé");
		for ($i = 0; $i<count($head); $i++){
			$excel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $head[$i]);
		}
		for ($i = 0; $i<count($data);$i++){
			for($j = 0; $j<count($data[0])/2;$j++){ 
				$excel->getActiveSheet()->setCellValueByColumnAndRow($j, $i+2, $data[$i][$j]);
			}
		}
		//Nomme la feuille
		$excel->getActiveSheet()->setTitle("Liste des inscrits");
		$excel->getActiveSheet()->setAutoFilter('D1:F1');
		//Format
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=FEH_Visiteurs.xls");
		header("Cache-Control: max-age=0");
		//Export
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('php://output'); 
	}	
	
	}
	
	
	


?>
