<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/cvtheque/front/view.php 0000 28-04-2011 Fofif $
 */

class CvthequeView extends WView {
	private $model;
	
	public function __construct(CvthequeModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillForm($model, $data) {
		foreach ($model as $item => $default) {
            /*if($item = 'mail' && !empty($_SESSION['userid'])) {
                $data['mail'] = $_SESSION['email'];
            }*/
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
	public function inscription($data = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Inscription au pôle conseil');
		$this->assign('css', '/apps/cvtheque/front/css/cvtheque.css');
		
		$this->assign('booking', $this->model->getBooking());
		$schedule = $this->model->getSchedule();
		foreach ($schedule as $s) {
			$s['schedule'] = explode(',', $s['schedule']);
			$this->tpl->assignBlockVars('intervenant', $s);
		}
		
		$this->fillForm(
			array(
				'mail' => @$_SESSION['email'], 
				'lastname' => '',
				'firstname' => '',
				'diplome' => '',
				'secteur' => '',
			),
			$data
		);
	}

    public function edit($data = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Inscription au pôle conseil');
		$this->assign('css', '/apps/cvtheque/front/css/cvtheque.css');
		
		$this->assign('booking', $this->model->getBooking());
		$schedule = $this->model->getSchedule();
		foreach ($schedule as $s) {
			$s['schedule'] = explode(',', $s['schedule']);
			$this->tpl->assignBlockVars('intervenant', $s);
		}
		
		$ecoles = array('Mines de Nancy', 'EEIGM', 'ENSAM Metz', 'ENSEM', 'ENSG', 'ENSGSI', 'ENSIC', 'EPITACH', 'ESIAL', 'ESSTIN', 'ICN Business School', 'INPL', 'IUP Finance', 'Nancy Université', 'Sciences Po', 'Université Henri Poincaré', 'Université Nancy 2');
		$this->assign('ecoles', $ecoles);
		$stages = array('Stage ouvrier', 'Stage 2A (2 mois)', 'Stage de fin d\'étude', 'Stage césure', 'Emploi', 'Alternance');
		$this->assign('stages', $stages);
		
		$data = $this->model->getBookingOfUser($_SESSION['userid']);
		$this->fillForm(
			array(
				'mail' => $data['email'], 
				'lastname' => $data['lastname'],
				'firstname' => $data['firstname'],
				'diplome' => $data['diplome'],
				'secteur' => $data['secteur'],
				'ecole' => $data['ecole'],
				'stage' => $data['recherche'],
			),
			$data
		);
	}
	
	public function entreprise($fecole, $frech) {
		$this->assign('pageTitle', 'Forum Est-Horizon | CV-thèque');
		$this->assign('css', '/apps/cvtheque/front/css/cvtheque.css');
		
		$ecoles = array('Mines de Nancy', 'EEIGM', 'ENSAM Metz', 'ENSEM', 'ENSG', 'ENSGSI', 'ENSIC', 'EPITACH', 'ESIAL', 'ESSTIN', 'ICN Business School', 'INPL', 'IUP Finance', 'Nancy Université', 'Sciences Po', 'Université Henri Poincaré', 'Université Nancy 2');
		$this->assign('ecoles', $ecoles);
		$stages = array('Stage ouvrier', 'Stage 2A (2 mois)', 'Stage de fin d\'étude', 'Stage césure', 'Emploi', 'Alternance');
		$this->assign('stages', $stages);
		$this->assign('fecole', $fecole);
		$this->assign('frech', $frech);
		
		$bookings = $this->model->getBookingData($fecole, $frech);
		foreach ($bookings as $b) {
			$this->tpl->assignBlockVars('bookings', $b);
		}
	}
}

?>