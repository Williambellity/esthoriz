<?php


class QuestController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new QuestModel();
		
		include 'view.php';
		$this->setView(new QuestView($this->model));
		
	}
	
	
	public function launch() {
		WNote::treatNoteSession();
		
		//$this->view->assign('css', '/apps/bonus/front/css/bonus.css');
		$this->view->assign('pageTitle', "Forum Est-Horizon | Réservation");
		
	
					
		$action = $this->getAskedAction();
		$this->forward($action, 'quest');
	}
	
	public function quest() {		
		// Les notes
		WNote::treatNoteSession();
		
		
		
		
		
		setlocale(LC_ALL, 'fr_CA.utf8'); // fr_FR pour la France

		$this->view->quest();
		
		$this->render('quest');
		
	}
	
}

?>
