<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/logistic/front/main.php 0001 14-05-2011 Fofif $
 */

class LogisticController extends WController {
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new LogisticModel();
		
		include 'view.php';
		$this->setView(new LogisticView($this->model));
	}

    private function getId($argN = 0) {
		$args = WRoute::getArgs();
		if (empty($args[0])) {
			return 0;
		} else {
			return intval($args[$argN]);
		}
	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'reservation');
	}
	
	public function reservation() {

        $firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId();
		if (empty($_SESSION['firmid']) && ($firmid == 0 || !isset($_SESSION['access']['all']))) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}

		$data = WRequest::get(array( 'type_stand', 'type_lot', 'color', 'more_chaise', 'more_table', 'more_tabouret', 'more_banque', 'more_presentoir', 'accessoires', 'options', 'enseigne', 'commentaire', 'price'), null, 'POST', false);
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'display');
			} else {
				if ($this->model->set_Firm($firmid,$data)) {
					WNote::success("Informations enregistrées", "Les informations concernant votre réservation de stand ont été enregistrées avec succès.",'display');
				} else {
					WNote::error("Erreur", "Une erreur s'est produite.", 'display');
				}
			}
		} else {
			$this->view->reservation($firmid);
			$this->render('reservation');
		}
	}
}

?>