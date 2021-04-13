<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/cvtheque/admin/main.php 0000 15-11-2011 Fofif $
 */

class CvthequeAdminController extends WController {
	private $fullAccess = false;
	private $firmAccess;
	
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'planning' => "Liste des inscriptions",
		'addTimeSlot' => "Ajout d'un créneau"
	);
	
	public function __construct() {
		// Chargement des modèles
		include 'model.php';
		$this->model = new CvthequeAdminModel();
		
		include 'view.php';
		$this->setView(new CvthequeAdminView($this->model));
	}
	
	public function launch() {
		// Les notes
		WNote::treatNoteSession();
		
		$this->forward($this->getAskedAction(), 'planning');
	}
	
	public function planning() {
		$this->render('planning');
	}
	
	public function addTimeSlot() {
		$data = WRequest::get(array('name', 'firm', 'firmid', 'desc', 'start', 'duration', 'number'), null, 'POST', false);
		// Le formulaire a-t-il été envoyé ?
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			/*
			 * VERIFICATIONS
			 */
			if (empty($data['name'])) {
				$erreurs[] = "Nom de l'intervenant manquant.";
			}
			
			foreach ($data['start'] as $s) {
				$array = explode(':', $s);
				if (sizeof($array) != 2) {
					$erreurs[] = "Problème de formattage dans le début de créneau. Utilisez le caractère ':' pour l'horaire.";
					break;
				}
				$array[0] = str_pad($array[0], 2, '0', STR_PAD_LEFT);
				$array[1] = str_pad($array[1], 2, '0', STR_PAD_LEFT);
			}
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->addTimeSlot();
				$this->render('time_slot');
			} else {
				// Compilation des créneaux
				$creneaux = array();
				foreach($data['start'] as $k => $start) {
					list($h, $m) = explode(':', $data['start'][$k]);
					$timestamp = mktime($h, $m);
					$length = sizeof($data['start']);
					for ($i = 0; $i < $data['number'][$k]; $i++) {
						$creneaux[] = date('H:i', $timestamp+$i*$data['duration'][$k]*60);
					}
				}
				$data['schedule'] = implode(',', $creneaux);
				
				$data['start'] = implode(',', $data['start']);
				$data['number'] = implode(',', $data['number']);
				$data['duration'] = implode(',', $data['duration']);
				
				// Enregistrement des créneaux
				if (!$this->model->addTimeSlot($data)) {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					$this->view->addTimeSlot();
					$this->render('time_slot');
				} else {
					WNote::success("Ajout de créneaux", "Les créneaux ont été créés avec succès.", 'session');
					// Redirection vers l'accueil
					header('location: '.WRoute::getDir().'admin/cvtheque/');
				}
			}
		} else {
			$this->view->addTimeSlot();
			$this->render('time_slot');
		}
	}
}

?>
