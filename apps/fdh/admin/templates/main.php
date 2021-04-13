<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/fdh/admin/main.php 0000 10-11-2012 Fofif $
 */

class FdhAdminController extends WController {
	// id des users pour le full control
	// 62 = FDH_forum
	// 363 = BDE_mines
	// 848 = BDE_MinesNancy
	// 450 = Brian logistique 2014
	// 611 = Nathan trésorier 2015
	//603 et 612=logistique 2015
	//602 : respo com'
	//684 RDD2015
	protected $authorizedUsers = array(848);
	
	protected $actionList = array(
		'main' => "Inscription",
		'liste' => "Liste des inscrits au départ",
		'del_entry' => "\Suppression d'un utilisateur",
		'excel_printaller' => "Imprimer la liste des inscrits au départ",
		'excel_printretour' => "Imprimer la liste des inscrits au retour"
		);
	
    /*
     * Chargement du modèle et de la view
     */
    public function __construct() {
        if ($_SESSION['accessString'] == 'all' || in_array($_SESSION['userid'], $this->authorizedUsers)) {
			$this->actionList['bus'] = "Gestion des bus";
			$this->actionList['bde_manager'] = "Gestion des BDE";
			$this->actionList['purge'] = "Vider les données";
		}
		
		include 'model.php';
		$this->model = new FdhModel();
		
		include 'view.php';
        $this->setView(new FdhAdminView($this->model));
    }

    public function launch() {
		WNote::treatNoteSession();
		
		$action = $this->getAskedAction();
		$this->forward($action, 'main');
    }
	
    public function main() {
        $data = WRequest::getAssoc(array('nom', 'prenom', 'busaller', 'busretour', 'paymod'));
		if (!in_array(null, $data, true)) {
			// Nombre de personnes à inscrire
			$count = count($data['nom']);
			$errors = array();
			$busaller_candidates = array();
			$busretour_candidates = array();
			$todo = array(); // Inscriptions à refaire
			$regexp = '/^[a-zA-Z-.ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ ]+$/';
			
			// Vérification des champs
			// + classement des gens par bus
			for ($i = 0; $i < $count; $i++) {
				// On ignore la ligne s'il manque des informations
				if (empty($data['nom'][$i]) && empty($data['prenom'][$i])) {
					continue;
				}
				
				if (empty($data['nom'][$i]) || !preg_match($regexp, $data['nom'][$i])) {
					$errors[] = "Le nom de l'entrée n°".($i+1)." est invalide (caractères alphanumériques et accentués uniquement).";
				}
				
				if (empty($data['prenom'][$i]) || !preg_match($regexp, $data['prenom'][$i])) {
					$errors[] = "Le prénom de l'entrée n°".($i+1)." est invalide (caractères alphanumériques et accentués uniquement).";
				}
				
				$data['busaller'][$i] = intval($data['busaller'][$i]);
				$data['busretour'][$i] = intval($data['busretour'][$i]);
				if ($data['busaller'][$i] <= 0) {
					$errors[] = "Veuillez choisir un bus aller pour l'entrée n°".($i+1);
				}

				if ($data['busretour'][$i] <= 0) {
					$errors[] = "Veuillez choisir un bus retour pour l'entrée n°".($i+1);
				}
				
				$data['paymod'][$i] = intval($data['paymod'][$i]);
				if ($data['paymod'][$i] <= 0) {
					$errors[] = "Veuillez choisir mode de paiement pour l'entrée n°".($i+1);
				}
				
				// Structure candidat
				$candidate = array(
					'nom' => $data['nom'][$i],
					'prenom' => $data['prenom'][$i],
					'paymod' => $data['paymod'][$i],
					'busaller' => $data['busaller'][$i],
					'busretour' => $data['busretour'][$i]
				);
				
				if (empty($errors)) {
					if (!isset($busaller_candidates[$data['busaller'][$i]])) { // on initialise le tableau
						$busaller_candidates[$data['busaller'][$i]] = array($candidate);
						$busretour_candidates[$data['busretour'][$i]] = array($candidate);
					} else { // on le rajoute
						$busaller_candidates[$data['busaller'][$i]][] = $candidate;
						$busretour_candidates[$data['busretour'][$i]][] = $candidate;
					}
				} else {
					// On refait les inscriptions qui ont passé les filtres
					if (!empty($busaller_candidates) or !empty($busreour_candidates)) {
						foreach ($busaller_candidates as $idb => $candidates) {
							$todo = array_merge($todo, $candidates);
						}
					}
					
					// Ajout du candidat à la liste à réafficher dans le formulaire + erreur
					$todo[] = $candidate;
				}
			}
			
			if (empty($todo)) { // Il n'y a pas eu d'erreurs
				$busaller_full = array(); // liste des bus pleins avec les gens dedans
				$busaller_seats_left = array();
				$busretour_full = array(); // liste des bus pleins avec les gens dedans
				$busretour_seats_left = array();
				$busaller_data = array();
				$busretour_data = array();
				// Parcours des bus
				foreach ($busaller_candidates as $idballer => $candidates) {
					// Si on souhaite inscrire plus de gens qu'il n'y a de places
					$busaller_seats_left[$idballer] = $this->model->placesLeft($idballer);
					if (count($candidates) > $busaller_seats_left[$idballer]) {
						// blacklist le bus
						$busaller_full[$idballer] = $candidates;
						unset($busaller_candidates[$idballer]);
						$busretour_full[$idbretour] = $candidates;
						unset($busretour_candidates[$idbretour]);
					}
					
					// Chargement des informations de bus
					$busaller_data[$idballer] = $this->model->getBusInfo($idballer);
				}

				foreach ($busretour_candidates as $idbretour => $candidates) {
					// Si on souhaite inscrire plus de gens qu'il n'y a de places
					$busretour_seats_left[$idbretour] = $this->model->placesLeft($idbretour);
					if (count($candidates) > $busretour_seats_left[$idbretour]) {
						// blacklist le bus
						$busretour_full[$idbretour] = $candidates;
						unset($busretour_candidates[$idbretour]);
					}
					
					// Chargement des informations de bus
					$busretour_data[$idbretour] = $this->model->getBusInfo($idbretour);
				}
				
				// Les gens restants peuvent être inscrits
				$success = "";
				$error = "";
				foreach ($busaller_candidates as $idballer => $candidates) {
					foreach ($candidates as $person) {
						$person['busaller'] = $idballer;
						$person['busretour'] = $idballer;
						// Chaîne pour l'affichage
						$str = $person['nom']." ".$person['prenom']." dans le bus qui va à ".$busaller_data[$idballer]['lieu'];//" à ".$bus_data[$idb]['heure'];
						if ($busaller_data[$idballer]['numero'] != 0) {
							$str .= " (numéro ".$busaller_data[$idballer]['numero'].")";
						}
						$str .= "<br />\n";
						
						if ($this->model->registerPerson($person, $idballer)) {
							$success .= $str;
						} else {
							$error .= $str;
							$todo[] = $person;
						}
					}
				}
				
				if (!empty($success)) {
					WNote::success("Les inscriptions suivantes ont été réalisées :", $success, 'assign');
				}
				
				if (!empty($error)) {
					WNote::error("Une erreur inconnue s'est produite pour ces inscriptions :", $error, 'assign');
				}
				
				// On gère les gens qui n'ont pas pu être inscrits
				foreach ($busaller_full as $idballer => $candidates) {
					$str = "Le bus qui part de ".$busaller_data[$idballer]['lieu'];/*" à ".$bus_data[$idb]['heure']*/
					if ($busaller_data[$idballer]['numero'] != 0) {
						$str .= " (numéro ".$busaller_data[$idballer]['numero'].")";
					}
					$str .= " ne dispose plus que de ".$busaller_seats_left[$idballer]." place(s).<br />\n"
						."Les personnes suivantes n'ont pas été inscrites :<br />\n";
					foreach ($candidates as $person) {
						$str .= $person['nom']." ".$person['prenom']."<br />\n";
						
						// On envoie à la view les inscriptions à refaire
						$todo[] = $person;
					}
					WNote::error("Bus complet", $str, 'assign');
				}

				foreach ($busretour_full as $idbretour => $candidates) {
					$str = "Le bus qui part de ".$busretour_data[$idbretour]['lieu'];/*" à ".$bus_data[$idb]['heure']*/
					if ($busretour_data[$idbretour]['numero'] != 0) {
						$str .= " (numéro ".$busretour_data[$idbretour]['numero'].")";
					}
					$str .= " ne dispose plus que de ".$busretour_seats_left[$idbretour]." place(s).<br />\n"
						."Les personnes suivantes n'ont pas été inscrites :<br />\n";
					foreach ($candidates as $person) {
						$str .= $person['nom']." ".$person['prenom']."<br />\n";
						
						// On envoie à la view les inscriptions à refaire
						$todo[] = $person;
					}
					WNote::error("Bus complet", $str, 'assign');
				}
				
				// Affichage
				$this->view->form($todo);
				$this->render('form');
			} else {
				WNote::error("Données incomplètes", implode('<br />', $errors), 'assign');
				/*foreach ($bus_candidates as $idb => $candidates) {
					$todo = array_merge($todo, $candidates);
				}*/
				$this->view->form($todo);
				$this->render('form');
			}
		} else {
			$this->view->form();
			$this->render('form');
		}
    }
    
    public function liste() {
		if (!in_array($_SESSION['userid'], $this->authorizedUsers) && !in_array('all', $_SESSION['access'])) {
			$data = $this->model->listPersons($_SESSION['userid']);
			$this->view->liste($data, false);
		} else {
			// Récupère tous les inscrits
			$data = $this->model->listPersons(0);
			$this->view->liste($data, true);
		}
        $this->render('liste');
		
		
    }
	
	public function excel_printaller(){
		//recuperer la liste des personnes
		$data = array(array());
		if (!in_array($_SESSION['userid'], $this->authorizedUsers) && !in_array('all', $_SESSION['access'])) {
			$data = $this->model->listPersons_version2($_SESSION['userid']);
		} 
		else {
			// Récupère tous les inscrits
			$data = $this->model->listPersons_version2(0);
		}
 		//impression dans excel
		require('helpers/excelPHP/PHPExcel.php');
		$excel = new PHPExcel();
		//Writing 
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Nom');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Prénom');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Lieu');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Heure');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Ecole');
		for ($i = 0; $i<count($data);$i++){
			for($j = 0; $j<count($data[0])/2;$j++){ //count($data[0]);$j++){
				$excel->getActiveSheet()->setCellValueByColumnAndRow($j, $i+2, $data[$i][$j]);
			}
		}
		//Nomme la feuille
		$excel->getActiveSheet()->setTitle("Liste des inscrits");
		$excel->getActiveSheet()->setAutoFilter('C1:E1');
		//Format
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=FEH_FDH.xls");
		header("Cache-Control: max-age=0");
		//Export
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('php://output'); 
	}
	
	public function excel_printretour(){
		//recuperer la liste des personnes
		$data = array(array());
		if (!in_array($_SESSION['userid'], $this->authorizedUsers) && !in_array('all', $_SESSION['access'])) {
			$data = $this->model->listPersons_version3($_SESSION['userid']);
		} 
		else {
			// Récupère tous les inscrits
			$data = $this->model->listPersons_version3(0);
		}
 		//impression dans excel
		require('helpers/excelPHP/PHPExcel.php');
		$excel = new PHPExcel();
		//Writing 
		$excel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Nom');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Prénom');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Lieu');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Heure');
		$excel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Ecole');
		for ($i = 0; $i<count($data);$i++){
			for($j = 0; $j<count($data[0])/2;$j++){ //count($data[0]);$j++){
				$excel->getActiveSheet()->setCellValueByColumnAndRow($j, $i+2, $data[$i][$j]);
			}
		}
		//Nomme la feuille
		$excel->getActiveSheet()->setTitle("Liste des inscrits");
		$excel->getActiveSheet()->setAutoFilter('C1:E1');
		//Format
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=FEH_FDH.xls");
		header("Cache-Control: max-age=0");
		//Export
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$objWriter->save('php://output'); 
	}
	

	public function bus() {
		$data = WRequest::getAssoc(array('bus_to_delete', 'idb', 'lieu', 'numero', 'heure', 'minute', 'max'));
		if (!in_array(null, $data, true)) {
			// Traitement des bus à supprimer
			$errors = array();
			$success = array();
			$bus_to_delete = explode(',', $data['bus_to_delete']);
			foreach ($bus_to_delete as $idb) {
				if (!empty($idb)) {
					$bus_data = $this->model->getBusInfo($idb);
					$str = "Le bus qui part de ".$bus_data['lieu']." à ".$bus_data['heure'];
					if ($bus_data['numero'] != 0) {
						$str .= " (numéro ".$bus_data['numero'].")";
					}
					
					// On vérifie qu'il n'y a aucun inscrit dans ce bus
					if ($this->model->countRegistered($idb) == 0) {
						if ($this->model->delBus($idb)) {
							$success[] = $str." a été supprimé avec succès.";
						} else {
							$errors[] = $str." a rencontré une erreur inconnue lors de sa suppression.";
						}
					} else {
						$errors[] = $str." n'a pas pu être supprimé car des gens sont déjà inscrits dedans.";
					}
				}
			}
			if (!empty($success)) {
				WNote::success("Bus supprimé", implode('<br />', $success), 'assign');
			}
			if (!empty($errors)) {
				WNote::error("Bus non supprimé", implode('<br />', $errors), 'assign');
			}
			
			$count = count($data['idb']);
			$errors = array();
			$success = array();
			for ($i = 0; $i < $count; $i++) {
				if (empty($data['lieu'][$i]) && empty($data['heure'][$i]) && empty($data['minute'][$i]) && empty($data['max'][$i])) {
					continue;
				}
				
				$idb = intval($data['idb'][$i]);
				$bus = array(
					'lieu' => $data['lieu'][$i],
					'numero' => intval($data['numero'][$i]),
					'heure' => (intval($data['heure'][$i]) % 24).':'.(intval($data['minute'][$i]) % 60),
					'max' => intval($data['max'][$i])
				);
				
				if (empty($bus['lieu'])) {
					$errors[] = "Veuillez préciser un lieu pour le bus n°".$i;
				}
				if (empty($bus['max'])) {
					$errors[] = "Veuillez préciser une limite maximale pour le bus n°".$i;
				}
				if (empty($bus['heure'])) {
					$errors[] = "L'horaire précisé pour le bus n°".$i." est erroné.";
				}
				
				if (empty($errors)) {
					if (!empty($idb)) {
						$bool = $this->model->updateBus($idb, $bus);
					} else {
						$bool = $this->model->createBus($bus);
					}
					$str = "Le bus qui part de ".$bus['lieu'];//" à ".$bus['heure'];
					if ($bus['numero'] != 0) {
						$str .= " (numéro ".$bus['numero'].")";
					}
					if ($bool) {
						$success[] = $str." a été édité avec succès.";
					} else {
						$errors[] = $str." a rencontré une erreur inconnue lors de son édition.";
					}
				}
			}
			if (!empty($success)) {
				WNote::success("Bus mis à jour", implode('<br />', $success), 'assign');
			}
			if (!empty($errors)) {
				WNote::error("Des erreurs se sont produites", implode('<br />', $errors), 'assign');
			}
		}
		$this->view->bus();
		$this->render('bus');
	}
	

	public function bde_manager() {
		// Options de tri
		$args = WRoute::getArgs();
		$page = empty($args[1]) ? 1 : $args[1];
		
		$data = WRequest::getAssoc(array('nickname', 'email'));
		// Le formulaire a-t-il été envoyé ?
		if (!in_array(null, $data, true)) {
			$data = array_merge($data, WRequest::getAssoc(array('password', 'password_confirm', 'id', 'onEdit')));
			$erreurs = array();
			
			if($data['onEdit']) {
				//Edition mode
				unset($data['onEdit']);
				
				//Test si id = BDE
				if($this->model->isIdBDE($data['id'])) {
					// Chargement des données actuelles
					$dbData = $this->model->getBDEData($data['id']);
				
					//Test du nickname
					if (empty($data['nickname'])) {
						$erreurs[] = "Pseudonyme manquant.";
					} else if ($data['nickname'] != $dbData['nickname'] && !$this->model->nicknameAvailable($data['nickname'])) {
						// On a vérifié que le pseudo a été changé
						$erreurs[] = "Ce pseudonyme est déjà réservé.";
					}
					
					// Passwords identiques
					if (!empty($data['password'])) {
						if ($data['password'] === $data['password_confirm']) {
							// Hashage
							$data['password'] = sha1($data['password']);
							unset($data['password_confirm']);
						} else {
							$erreurs[] = "Les mots de passe sont différents.";
						}
					} else {
						unset($data['password']);
					}
					unset($data['password_confirm']);
					
					// Email
					if (!empty($data['email']) && !preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i', $data['email'])) {
						$erreurs[] = "L'email fourni est invalide.";
					} else if ($data['email'] != $dbData['email'] && !$this->model->emailAvailable($data['email'])) {
						$erreurs[] = "Cet email est déjà pris.";
					}
					
					// En cas d'erreur
					if (!empty($erreurs)) {
						WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
						$this->view->bde_manager($page,$data);
						$this->render('bde_manager');
					} else {
						// Mise à jour des infos
						if ($this->model->updateBDE($data)) {
							WNote::success("Edition d'utilisateur", "L'utilisateur <strong>".$data['nickname']."</strong> a été mis à jour avec succès.", 'session');
							header('location: '.WRoute::getDir().'admin/fdh/bde_manager/'.$page);
						} else {
							WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
							$this->view->bde_manager($page);
							$this->render('bde_manager');
						}
					}
				} else {
					WNote::error("Erreur lors de l'édition", "Aucun utilisateur ne corresponde à cet identifiant.", 'assign');
					$this->view->bde_manager($page);
					$this->render('bde_manager');
				}
			} else {
				// Vérification du pseudo
				if (empty($data['nickname'])) {
					$erreurs[] = "Pseudonyme manquant.";
				} else if (!$this->model->nicknameAvailable($data['nickname'])) {
					// On a vérifié que le pseudo a été changé
					$erreurs[] = "Ce pseudonyme est déjà réservé.";
				}
				
				// Passwords identiques
				if (!empty($data['password'])) {
					if ($data['password'] === $data['password_confirm']) {
						// Hashage
						$data['pass'] = sha1($data['password']);
						unset($data['password_confirm']);
					} else {
						$erreurs[] = "Les mots de passe sont différents.";
					}
				} else {
					unset($data['password']);
				}
				unset($data['password_confirm']);
				
				// Email
				if (!empty($data['email']) && !preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i', $data['email'])) {
					$erreurs[] = "L'email fourni est invalide.";
				} else if (!$this->model->emailAvailable($data['email'])) {
					$erreurs[] = "Cet email est déjà pris.";
				}
				
				// En cas d'erreur
				if (!empty($erreurs)) {
					WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
					$this->view->bde_manager($page);
					$this->render('bde_manager');
				} else {
					// Mise à jour des infos
					if ($this->model->addBDE($data)) {
						WNote::success("Ajout d'utilisateur", "L'utilisateur <strong>".$data['nickname']."</strong> a été mis à jour avec succès.", 'session');
						header('location: '.WRoute::getDir().'admin/fdh/bde_manager/'.$page);
					} else {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
						$this->view->bde_manager($page);
						$this->render('bde_manager');
					}
				}
			}
		}
		
		$this->view->bde_manager($page);
		$this->render('bde_manager');
	}
	
	protected function del() {
		$args = WRoute::getArgs();
		$userid = intval($args[1]);
		if ($this->model->isIdBDE($userid)) {
			if (WRequest::get('confirm', null, 'POST') === '1') {
				$this->model->deleteBDE($userid);
				WNote::success("Suppression d'utilisateur", "L'utilisateur a été supprimé avec succès.", 'session');
				header('location: '.WRoute::getDir().'admin/fdh/bde_manager/');
			} else {
				$this->view->del($userid);
				$this->render('del');
			}
		} else {
			$this->bde_manager();
		}
	}
	
	protected function del_entry() {
		$args = WRoute::getArgs();
		$personid = intval($args[1]);
		$data = $this->model->getPerson($personid);
		if (!empty($data)) {
			if ($data['ecole'] == $_SESSION['userid'] || in_array($_SESSION['userid'], $this->authorizedUsers) || in_array('all', $_SESSION['access'])) {
				$this->model->deletePerson($personid);
				WNote::success("Inscription supprimée", $data['nom']." a été supprimé avec succès.", 'session');
			} else {
				WNote::error("Droits insuffisants", "Vous n'avez pas les droits pour supprimer cette inscription.", 'session');
			}
		} else {
			WNote::error("Inscription introuvable", "Cette inscription n'existe pas dans la base de données.", 'session');
		}
		header('location: '.WRoute::getDir().'admin/fdh/liste/');
	}
	
	protected function purge() {
		if (isset($_POST['confirm'])) {
			if (strtoupper($_POST['confirm']) == 'SUPPRIMER') {
				$this->model->deletePersons();
				WNote::success("Données vidées", "Les inscriptions ont été supprimées de la base de données.", 'session');
				header('location: '.WRoute::getDir().'admin/fdh/liste/');
			} else {
				WNote::error("Mot mal orthographié", "Le mot SUPPRIMER n'a pas été correctement détecté. Voulez-vous vraiment supprimer les données ?", 'assign');
				$this->render('purge');
			}
		} else {
			$this->render('purge');
		}
	}
}

?>
