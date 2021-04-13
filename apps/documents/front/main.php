<?php

/**

 * Wity CMS

 * Système de gestion de contenu pour tous.

 *

 * @author Julien1619

 * @version	$Id: apps/profil/front/main.php 0001 11-06-2011 Julien1619 $

 */



class DocumentsController extends WController {



	public function __construct() {

		include 'model.php';

		$this->model = new ProfilModel();

		

		include 'view.php';

		$this->setView(new ProfilView($this->model));

	}

	

	public function launch() {

		if (!empty($_SESSION['nickname'])) {

			$action = $this->getAskedAction();

			$this->forward($action, 'profil');

		} else {

			WNote::error("Accès interdit", "Vous devez être connecté pour accéder à cette zone.", 'display');

		}

	}

	

	public function profil() {

		$data = WRequest::get(array( 'civilite', 'nom', 'prenom', 'poste', 'langue', 'adresse', 'tel_fixe', 'tel_portable', 'fax'), null, 'POST', false);

		if (!in_array(null, $data, true)) {

			if ($this->model->updateContact($data)) {

				WNote::success("Profil mis à jour", "Votre profil a été correctement mis à jour.", 'assign');

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

