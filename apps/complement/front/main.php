<?php

/**

 * Wity CMS

 * Système de gestion de contenu pour tous.

 *

 * @author Julien1619

 * @version	$Id: apps/profil/front/main.php 0001 11-06-2011 Julien1619 $

 */



class ComplementController extends WController {



	public function __construct() {

		include 'model.php';

		$this->model = new ProfilModel();

		

		include 'view.php';

		$this->setView(new ProfilView($this->model));

	}

	

	public function launch() {

		if (!empty($_SESSION['nickname']) || isset($_GET["userid"])) {

			$action = $this->getAskedAction();

			$this->forward($action, 'profil');

		} else {

			WNote::error("Accès interdit", "Vous devez être connecté pour accéder à cette zone.", 'display');

		}

	}

	

	public function profil() {

		$data = WRequest::get(array( 'repas_nbr', 'repas_info', 'intervenant_nbr', 'intervenant_nom', 'parking', 'handicafe'), null, 'POST', false);

		if (!in_array(null, $data, true)) {

			if ($this->model->updateContact($data)) {

				WNote::success("Données mise à jour", "Vos données ont été correctement mis à jour.", 'assign');

				$this->view->profil();

				$this->render('profil');

			} else {

				WNote::error("Erreur", "Une erreur s'est produite.", 'display');

			}

		} else {

			$this->view->profil();

			$this->render('profil');

		}

	}
	

}



?>

