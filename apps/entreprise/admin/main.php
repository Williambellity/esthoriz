<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/admin/main.php 0007 25-08-2011 Fofif $
 */

class EntrepriseAdminController extends WController {
	private $fullAccess = false;
	private $firmAccess;
	
	/*
	 * Les opérations du module
	 */
	protected $actionList = array(
		'dashboard' => "Tableau de bord",
		'shoutbox_post' => "\\",
		'liste' => "Liste des entreprises",
		'waiting' => "Contacts en attente (%d)",
		'add' => "Ajouter une entreprise",
		'fiche' => "\Fiche d'une entreprise",
		'del' => "\Suppression d'une entreprise",
		'contact_add' => "\Ajout d'un contact à une entreprise",
		'contact_edit' =>  "\Edition d'un contact d'une entreprise",
		'contact_del' => "\Suppression d'un contact",
		'contact_treat' => "\Traitement de la demande d'inscription d'un contact",
		'contact_refuse' => "\Refus d'une demande d'inscription",
		'cat' => "Secteurs d'activité",
		'cat_del' => "\\",
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
				$action = 'dashboard';
			}
		}
		
		// Vérification de l'accès
		if ($this->checkFirmAccess($action)) {
			$this->actionList['waiting'] = sprintf($this->actionList['waiting'], $this->model->countWaitingContacts());
			$this->firmAccess = true;
		} else {
			$this->firmAccess = false;
		}
	}
	
	/**
	 * Vérifie l'accès de l'utilisateur en fonction de l'action demandée et de son niveau
	 */
	private function checkFirmAccess($action) {
		if (isset($_SESSION['access']['all'])) {
			$this->fullAccess = true;
			return true;
		} else if (isset($_SESSION['access']['entreprise'])) {
			// L'utilisateur a le niveau pour administrer toute l'appli
			if ($_SESSION['access']['entreprise'] == 1) {
				$this->fullAccess = true;
				return true;
			} else if ($_SESSION['access']['entreprise'] == 2) {
				$this->actionList['dashboard'] = '\\';
				$this->actionList['liste'] = '\\';
			}
			
			// Retrait de quelques actions du menu
			$this->actionList['waiting'] = '\\'.$this->actionList['waiting'];
			$this->actionList['add'] = '\\'.$this->actionList['add'];
			$this->actionList['cat'] = '\\'.$this->actionList['cat'];
			
			switch ($action) {
				case 'dashboard':
					return $_SESSION['access']['entreprise'] != 2;
				
				case 'liste':
					return $_SESSION['access']['entreprise'] != 2;
				case 'fiche':
				case 'participants':
					return true;
				
				case 'contact_add':
					// Vérifier que l'entreprise est sous contrôle
					$firmid = $this->getId();
					$firmData = $this->model->loadFirm($firmid);
					return $firmData['phoning_user'] == $_SESSION['userid'];
				
				case 'contact_edit':
				case 'contact_del':
					// Vérifier que le contact appartient à une entreprise sous contrôle
					$cid = $this->getId();
					$contactData = $this->model->loadContact($cid);
					if (!empty($contactData['firmid'])) {
						$firmData = $this->model->loadFirm($contactData['firmid']);
						return $firmData['phoning_user'] == $_SESSION['userid'];
					} else {
						return false;
					}
				
				default:
					return false;
			}
		} else {
			return false;
		}
	}
	
	public function launch() {
		if (isset($_SESSION['access']['entreprise']) && $_SESSION['access']['entreprise'] == 2) {
			$action = 'participants';
		} else {
			$action = $this->getAskedAction();
		}
		
		// Vérification du niveau de l'utilisateur
		if ($this->firmAccess) {
			$this->forward($action, 'dashboard');
		} else {
			WNote::error("Action non autorisée", "Vous ne disposez pas du niveau suffisant pour effectuer l'action demandée", 'display');
		}
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
	 * Affichage des contacts en attente de validation
	 */
	public function waiting() {
		// Les notes
		WNote::treatNoteSession();
		
		$this->view->contact_waiting();
		$this->render('contact_waiting');
	}
	
	/**
	 * Dashboard du module entreprise
	 */
	protected function dashboard() {
		// Traitement du tri
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[1]);
		$sortBy = empty($sortData) ? '' : array_shift($sortData);
		$sens = empty($sortData) ? '' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		
		// Les notes
		WNote::treatNoteSession();
		
		$this->view->dashboard($sortBy, $sens, $page);
		$this->render('dashboard');
	}
	
	protected function shoutbox_post() {
		$message = WRequest::get('message');
		if (!empty($message)) {
			$this->model->insertShoutboxMessage($message);
			WNote::success("Message envoyé", "Votre message a été posté dans la shoutbox.", 'session');
		}
		header('location: '.WRoute::getDir().'admin/entreprise/dashboard/');
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
	 * Ajout d'une entreprise
	 */
	protected function add() {
		$data = WRequest::getAssoc(array('name', 'cat', 'adress', 'city', 'postal_code', 'country'));
		$contact = WRequest::getAssoc(array('civilite', 'nom', 'prenom', 'poste', 'langue', 'adresse_contact', 'tel_fixe', 'tel_portable', 'fax', 'email'));
		$reducs = WRequest::get('reducs');
		if (!empty($reducs)) {
			$a = array();
			foreach ($reducs as $key => $v) {
				$a[] = $key;
			}
			$reducs = implode('#', $a);
		} else {
			$reducs = "";
		}
		// Le formulaire a-t-il été envoyé ?
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			/*
			 * VARIABLES ENTREPRISE
			 */
			// Vérifier la dispo du nom de l'entreprise
			if (empty($data['name'])) {
				$erreurs[] = "Nom de l'entreprise manquant.";
			} else if (!$this->model->firmNameAvailable($data['name'])) {
				$erreurs[] = "Cette entreprise existe déjà.";
			}
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->add(array_merge($data, $contact));
				$this->render('add');
			} else {
				// Ajout de l'entreprise dans la bdd
				if (!$this->model->createFirm($data)) {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					$this->view->add(array_merge($data, $contact));
					$this->render('add');
				} else {
					// ====> Création du contact <====
					// On vérifie que les données de contact ne sont pas vides en virant les valeurs nulles
					// pour savoir si on cherche à créer un contact
					// (il ne reste en principe que le champ "civilite")
					if (count(array_diff($contact, array(''))) > 1) {
						$contact['firmid'] = $this->model->getLastFirmId();
						$contact['adresse'] = $contact['adresse_contact'];
						unset($contact['adresse_contact']);
						if (WRequest::get('create') == 'on') {
							$user = WRequest::getAssoc(array('nickname', 'pass1', 'pass2'));
							$contactErrors = $this->createContact($contact, $user);
						} else {
							$contactErrors = $this->createContact($contact);
						}
					}
					
					if (!empty($contactErrors)) {
						WNote::error("Erreur lors de la création du contact", implode("<br />\n", $contactErrors), 'session');
					}
					// ====> Définition des réductions <===
					if(!$this->model->setFirmReduc($this->model->getLastFirmId(),$reducs)) {
						WNote::error("Erreur lors de l'ajout des réductions", "Une erreur s'est produite lors de l'enregistrement des réductions associées à l'entreprise", 'session'); 
					}
					
					WNote::success("Ajout d'entreprise", "L'entreprise <strong>".$data['name']."</strong> a été ajoutée avec succès.", 'session');
					// Redirection vers la fiche entreprise
					header('location: '.WRoute::getDir().'admin/entreprise/fiche/'.$this->model->getLastFirmId().'-'.urlencode($data['name']));
					// Historique
					$this->model->history($contact['firmid'], "Ajout d'une entreprise");
				}
			}
		} else {
			$this->view->add();
			$this->render('add');
		}
	}
	
	/**
	 * Affichage de la fiche d'une entreprise
	 */
	protected function fiche() {
		$firmid = $this->getId();
		$firmData = $this->model->loadFirm($firmid);
		if (empty($firmData)) {
			WNote::error("Entreprise invalide", "Cette entreprise n'existe pas.", 'session');
			header('location: '.WRoute::getDir().'admin/entreprise/liste/');
			return;
		}
		
		if ($this->fullAccess || $firmData['phoning_user'] == $_SESSION['userid']) {
			$firm = WRequest::getAssoc(array('name', 'cat', 'adress', 'city', 'postal_code', 'country', 'phoning_user', 'phoning_step', 'phoning_comment'));
			// Le formulaire a-t-il été envoyé ?
			if (!in_array(null, $firm, true)) {
				$erreurs = array();
				$dbData = $this->model->loadFirm($firmid);
				
				// Réductions
				$reducs = WRequest::get('reducs');
				if (!empty($reducs)) {
					$a = array();
					foreach ($reducs as $key => $v) {
						$a[] = $key;
					}
					$reducs = implode('#', $a);
				} else {
					$reducs = "";
				}
				
				/*
				 * VARIABLES ENTREPRISE
				 */
				// Vérifier la dispo du nom de l'entreprise
				if (empty($firm['name'])) {
					$erreurs[] = "Nom de l'entreprise manquant.";
				} else if ($firm['name'] != $dbData['name'] && !$this->model->firmNameAvailable($firm['name'], true)) {
					$erreurs[] = "Cette entreprise existe déjà.";
				}
				
				// En cas d'erreur
				if (!empty($erreurs)) {
					WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
					$this->view->fiche($firmid, $firm);
					$this->render('fiche_edit');
				} else {
					// Inscription de l'entreprise cf checkbox[name=registered]
					list($old_registered, $registered) = WRequest::get(array('old_registered', 'registered'));
					$registered = is_null($registered) ? 0 : 1;
					if ($old_registered != $registered) {
						if ($registered) {
							$this->model->register($firmid);
						} else {
							$this->model->unregister($firmid);
						}
					}
					
					// Update de l'entreprise dans la bdd
					if (!$this->model->updateFirm($firmid, $firm)) {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
						$this->view->fiche($firmid, $firm);
						$this->render('fiche_edit');
					} else {
						// ====> Définition des réductions <===
						if(!$this->model->setFirmReduc($firmid,$reducs)) {
							WNote::error("Erreur lors de l'ajout des réductions", "Une erreur s'est produite lors de l'enregistrement des réductions associées à l'entreprise", 'session'); 
						}
						WNote::success("Mise à jour d'entreprise", "L'entreprise <strong>".$firm['name']."</strong> a été mise à jour avec succès.", 'session');
						// Redirection vers la fiche entreprise
						header('location: '.WRoute::getDir().'admin/entreprise/fiche/'.$firmid);
						// Historique
						$this->model->history($firmid, "Edition d'une entreprise");
					}
				}
			} else {
				// Les notes
				WNote::treatNoteSession();
				
				$this->view->fiche($firmid);
				$this->render('fiche_edit');
			}
		} else {
			$this->view->fiche($firmid);
			$this->render('fiche_read');
		}
	}
	
	/**
	 * Suppression d'une entreprise
	 */
	protected function del() {
		$firmid = $this->getId();
		if (WRequest::get('confirm') === '1') {
			$firmData = $this->model->loadFirm($firmid);
			
			include APP_DIR.'user/admin/model.php';
			$userModel = new UserAdminModel();
			
			// Traitement des contacts
			$contacts = $this->model->loadFirmContacts($firmid);
			foreach ($contacts as $c) {
				// Suppression du compte utilisateur
				if (!empty($c['userid'])) {
					$userModel->deleteUser($c['userid']);
				}
				// Suppression du contact
				$this->model->deleteContact($c['id']);
				
				// Historique
				$this->model->history($firmid, "Suppression d'un contact", $c['id']);
			}
			
			//Suppression des réductions
			$this->model->delFirmReduc($firmid);
			
			// Suppression de l'entreprise
			$this->model->deleteFirm($firmid);
			
			// Message
			WNote::success("Suppression d'une entreprise", "L'entreprise \"".$firmData['name']."\" a été supprimée avec succès.", 'session');
			header('location: '.WRoute::getDir().'admin/entreprise/liste/');
			
			// Historique
			$this->model->history($firmid, "Suppression d'une entreprise");
			$this->model->unregister($firmid);
		} else {
			$this->view->del($firmid);
			$this->render('del');
		}
	}
	
	protected function createContact($contact, $user = array()) {
		$erreurs = array();
		
		// Traitement des numéros de tel
		$contact['tel_fixe'] = preg_replace('#[^0-9+()]#', '', $contact['tel_fixe']);
		$contact['tel_portable'] = preg_replace('#[^0-9+()]#', '', $contact['tel_portable']);
		$contact['fax'] = preg_replace('#[^0-9+()]#', '', $contact['fax']);
		$contact['email'] = strtolower($contact['email']);
		
		// Traitement du compte utilisateur
		if (!empty($user)) {
			include APP_DIR.'user/admin/model.php';
			$userModel = new UserAdminModel();
			
			if (empty($user['nickname'])) {
				$erreurs[] = "L'identifiant du compte utilisateur pour le contact est invalide.";
			} else if (!$userModel->nicknameAvailable($user['nickname'])) {
				$erreurs[] = "Cet identifiant de compte utilisateur est déjà réservé.";
			}
			if (strpos($user['nickname'], '@') !== false) {
				$user['nickname'] = strtolower($user['nickname']);
			}
			
			// Passwords identiques
			if (!empty($user['pass1'])) {
				if ($user['pass1'] === $user['pass2']) {
					$user['pass1'] = sha1($user['pass2']);
				} else {
					$erreurs[] = "Les mots de passe pour le compte utilisateur sont différents.";
				}
			} else {
				$erreurs[] = "Veuillez choisir un mot de passe pour le compte utilisateur.";
			}
		}
		
		if (empty($erreurs)) {
			if (!empty($user)) {
				$userData = array(
					'nickname' => $user['nickname'],
					'pass' => $user['pass1'],
					'email' => $contact['email'],
					'access' => '',
					'groupe' => 4 // ATTENTION : ID DE LA CAT ENTREPRISE
				);
				if (!$userModel->createUser($userData)) {
					WNote::error("Erreur création compte", "Le compte utilisateur du contact ".$contact['nom']." n'a pas été correctement créé.", 'session');
				} else {
					// Envoi de l'email éventuellement
				}
				$contact['userid'] = $userModel->getLastUserId();
			} else {
				$contact['userid'] = 0;
			}
			
			// Ajout du contact dans la bdd
			if ($this->model->createContact($contact)) {
				// Historique
				$this->model->history($contact['firmid'], "Création d'un contact", $this->model->getLastContactId());
				return array();
			} else {
				return array("Une erreur inconnue s'est produite lors de la création du contact");
			}
		} else {
			return $erreurs;
		}
	}
	
	/**
	 * Ajout d'un contact
	 */
	protected function contact_add() {
		$firmid = $this->getId();
		
		if (!$this->model->validFirmId($firmid)) {
			WNote::error("Entreprise introuvable", "Vous tentez d'ajouter un contact à une entreprise inexistante.", 'session');
			// Redirection vers la page entreprise
			header('location: '.WRoute::getDir().'admin/entreprise/');
			return;
		}
		
		$contact = WRequest::get(array('civilite', 'nom', 'prenom', 'poste', 'langue', 'adresse', 'tel_fixe', 'tel_portable', 'fax', 'email'));
		$user = WRequest::get(array('nickname', 'pass1', 'pass2'));
		if (!in_array(null, $contact, true)) {
			// Rajout du firmid
			$contact['firmid'] = intval($firmid);
			
			if (WRequest::get('create') == 'on') {
				$user = WRequest::getAssoc(array('nickname', 'pass1', 'pass2'));
				$contactErrors = $this->createContact($contact, $user);
			} else {
				$contactErrors = $this->createContact($contact);
			}
			
			// En cas d'erreur
			if (empty($contactErrors)) {
				WNote::success("Ajout d'un contact", "Le contact <strong>".$contact['nom']."</strong> a été ajouté avec succès.", 'session');
				
				// Redirection vers la fiche entreprise
				header('location: '.WRoute::getDir().'admin/entreprise/fiche/'.$firmid);
			} else {
				WNote::error("Informations invalides", implode("<br />\n", $contactErrors), 'assign');
				$this->view->contact_add($firmid, $contact);
				$this->render('contact_add');
			}
		} else {
			$this->view->contact_add($firmid);
			$this->render('contact_add');
		}
	}
	
	/**
	 * Edition d'un contact
	 */
	protected function contact_edit() {
		$cid = $this->getId();
		
		$contact = WRequest::getAssoc(array('firmid', 'userid', 'civilite', 'nom', 'prenom', 'poste', 'langue', 'adresse', 'tel_fixe', 'tel_portable', 'fax', 'email'));
		$user = WRequest::getAssoc(array('nickname', 'pass1', 'pass2'));
		// Le formulaire a-t-il été envoyé ?
		if (!in_array(null, $contact, true)) {
			$erreurs = array();
			
			// Traitement des numéros de tel
			$contact['tel_fixe'] = preg_replace('#[^0-9+()]#', '', $contact['tel_fixe']);
			$contact['tel_portable'] = preg_replace('#[^0-9+()]#', '', $contact['tel_portable']);
			$contact['fax'] = preg_replace('#[^0-9+()]#', '', $contact['fax']);
			$contact['firmid'] = intval($contact['firmid']);
			$contact['userid'] = intval($contact['userid']);
			$contact['email'] = strtolower($contact['email']);
			
			// Ce contact possède déjà un compte utilisateur
			include APP_DIR.'user/admin/model.php';
			$userModel = new UserAdminModel();
			if (!empty($contact['userid'])) {
				if (WRequest::get('delAccount') == 'on') {
					$userModel->deleteUser($contact['userid']);
					$contact['userid'] = 0;
				} else {
					$userUpdate = array();
					$userData = $userModel->getUserData($contact['userid']);
					if (!empty($user['nickname'])) {
						if ($user['nickname'] != $userData['nickname']) {
							$userUpdate['nickname'] = $user['nickname'];
						}
					}
					
					// Mise à jour du password si demandé
					if (!empty($user['pass1'])) {
						if ($user['pass1'] === $user['pass2']) {
							$userUpdate['password'] = sha1($user['pass1']);
						} else {
							$erreurs[] = "Les mots de passe fournis pour le compte utilisateur sont différents.";
						}
					}
					
					// Mise à jour des données
					if (empty($erreurs) && !empty($userUpdate)) {
						$userModel->updateUser($contact['userid'], $userUpdate);
					}
				}
			} else if (WRequest::get('create') == 'on') {
				// Demande de création d'un compte utilisateur
				if (empty($user['nickname'])) {
					$erreurs[] = "L'identifiant du compte utilisateur pour le contact est invalide.";
				} else if (!$userModel->nicknameAvailable($user['nickname'])) {
					$erreurs[] = "Cet identifiant de compte utilisateur est déjà réservé.";
				}
				if (strpos($user['nickname'], '@') !== false) {
					$user['nickname'] = strtolower($user['nickname']);
				}
				
				// Passwords identiques
				if (!empty($user['pass1'])) {
					if ($user['pass1'] === $user['pass2']) {
						$user['pass1'] = sha1($user['pass2']);
					} else {
						$erreurs[] = "Les mots de passe pour le compte utilisateur sont différents.";
					}
				} else {
					$erreurs[] = "Veuillez choisir un mot de passe pour le compte utilisateur.";
				}
				
				if (empty($erreurs)) {
					// Création du compte utilisateur du contact
					$userData = array(
						'nickname' => $user['nickname'],
						'pass' => $user['pass1'],
						'email' => $contact['email'],
						'access' => '',
						'groupe' => 4 // ATTENTION : ID DE LA CAT ENTREPRISE
					);
					if (!$userModel->createUser($userData)) {
						WNote::success("Erreur création compte", "Le compte utilisateur du contact ".$contact['nom']." n'a pas été correctement créé.", 'session');
					} else {
						$contact['userid'] = $userModel->getLastUserId();
					}
				}
			}
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->contact_edit($cid, $contact);
				$this->render('contact_edit');
			} else {
				// Edition du contact dans la bdd
				if (!$this->model->updateContact($cid, $contact)) {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
					$this->view->contact_edit($cid, $contact);
					$this->render('contact_edit');
				} else {
					WNote::success("Edition d'un contact", "Le contact <strong>".$contact['nom']."</strong> a été édité avec succès.", 'session');
					// Redirection vers la fiche entreprise
					header('location: '.WRoute::getDir().'admin/entreprise/fiche/'.$contact['firmid']);
					// Historique
					$this->model->history($contact['firmid'], "Edition d'un contact", $cid);
				}
			}
		} else {
			// Les notes
			WNote::treatNoteSession();
			
			if ($this->model->validContactId($cid)) {
				$this->view->contact_edit($cid);
				$this->render('contact_edit');
			} else {
				WNote::error("Contact introuvable", "Le contact demandé n'existe pas ou plus dans la base de données.", 'display');
			}
		}
	}
	
	/**
	 * Controler pour la suppression d'un contact
	 */
	protected function contact_del() {
		$cid = $this->getId();
		if (WRequest::get('confirm') === '1') {
			$contactData = $this->model->loadContact($cid);
			
			// Suppression du compte utilisateur
			if (!empty($contactData['userid'])) {
				include APP_DIR.'user/admin/model.php';
				$userModel = new UserAdminModel();
				$userModel->deleteUser($contactData['userid']);
			}
			
			// Suppression du contact
			$this->model->deleteContact($cid);
			
			// Message
			WNote::success("Suppression du contact", "L'utilisateur ".$contactData['nom']." a été supprimé avec succès.", 'session');
			header('location: '.WRoute::getDir().'admin/entreprise/fiche/'.$contactData['firmid']);
			
			// Historique
			$this->model->history($contactData['firmid'], "Suppression d'un contact", $cid);
		} else {
			$this->view->contact_del($cid);
			$this->render('contact_del');
		}
	}
	
	/**
	 * Traitement de la demande d'un contact
	 */
	protected function contact_treat() {
		// Les notes
		WNote::treatNoteSession();
		
		$cid = $this->getId();
		if (!$this->model->validWaitingContactId($cid)) {
			WNote::error("Contact introuvable", "Le contact n°".$cid." n'est pas un contact en attente de validation.", 'session');
			header('location: '.WRoute::getDir().'admin/entreprise/waiting/');
			return;
		}
		
		$contact = WRequest::getAssoc(array('firmid', 'firm', 'civilite', 'nom', 'prenom', 'poste', 'langue', 'adresse', 'tel_fixe', 'tel_portable', 'fax'));
		// Le formulaire a-t-il été envoyé ?
		if (!in_array(null, $contact, true)) {
			$erreurs = array();
			$dbData = $this->model->loadContact($cid);
			
			$contact['firmid'] = intval($contact['firmid']);
			
			// Détermination du contact à remplacer
			$remplace = WRequest::get('remplace');
			$remplaceId = 0;
			if (sizeof($remplace) > 1) {
				$erreurs[] = "Vous ne pouvez pas remplacer deux contacts.";
			} else if (sizeof($remplace) == 1) {
				$remplaceId = array_shift(array_keys($remplace));
				if (!$this->model->validContactId($remplaceId)) {
					$erreurs[] = "Vous tentez de remplacer le contact #".$remplaceId." mais il n'existe pas dans la base de données.";
				}
			}
			
			// Vérification pour la création d'entreprise
			if (empty($contact['firmid'])) {
				if (empty($contact['firm'])) {
					$erreurs[] = "Veuillez spécifier un nom d'entreprise pour ce contact";
				} else if (!$this->model->firmNameAvailable($contact['firm'])) {
					$erreurs[] = "Il semble que vous souhaitiez accepter ce contact en créant une nouvelle entreprise dans la base de données.<br />
						Cependant, une entreprise du même nom existe déjà.<br />
						Veillez à bien utiliser l'assistant de sélection d'une entreprise lorsque vous effectuez la recherche.";
				}
			}
			
			// Formattage de quelques variables
			$contact['tel_fixe'] = preg_replace('#[^0-9+()]#', '', $contact['tel_fixe']);
			$contact['tel_portable'] = preg_replace('#[^0-9+()]#', '', $contact['tel_portable']);
			$contact['fax'] = preg_replace('#[^0-9+()]#', '', $contact['fax']);
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->contact_treat($cid, $contact);
				$this->render('contact_treat');
			} else {
				// Création de l'entreprise
				if (empty($contact['firmid'])) {
					if (!$this->model->createBlankFirm($contact['firm'])) {
						WNote::error("Erreur lors de la création de l'entreprise", "Une erreur inconnue s'est produite.", 'assign');
						$this->view->contact_treat($cid, $contact);
						$this->render('contact_treat');
						return;
					}
					$contact['firmid'] = $this->model->getLastFirmId();
					
					// Historique
					$this->model->history($contact['firmid'], "Création d'une entreprise");
				} else {
					// Suppression du contact qu'on désire remplacer
					if (!empty($remplaceId)) {
						$remplaceData = $this->model->loadContact($remplaceId);
						
						// Suppression du compte utilisateur
						if (!empty($remplaceData['userid'])) {
							include APP_DIR.'user/admin/model.php';
							$userModel = new UserAdminModel();
							$userModel->deleteUser($remplaceData['userid']);
						}
						
						// Suppression du contact
						$this->model->deleteContact($remplaceId);
					}
				}
				$contact['firm_asked'] = ''; // remise à zero du champ indiquant que le contact est en attente
				unset($contact['firm']);
				
				// Mise à jour de l'entreprise à l'étape phoning "En cours d'inscription"
				$this->model->updateFirm($contact['firmid'], array('phoning_step' => 5));
				
				// Suppression des doublons comptes utilisateurs
				$this->model->cleanOtherUsers($dbData['userid'], $dbData['email']);
				// Peu importe que l'email ait été confirmé ou non, remise à 0 du champ confirm
				$this->model->resetUserConfirm($dbData['userid']);
				
				// Edition du contact dans la bdd
				if (!$this->model->updateContact($cid, $contact)) {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
					$this->view->contact_treat($cid, $contact);
					$this->render('contact_treat');
				} else {
					if (!empty($remplaceId)) {
						// Modification de l'id du contact
						$this->model->updateContact($cid, array('id' => $remplaceId));
						$cid = $remplaceId;
					}
					
					// Email de confirmation
					list($send, $body) = WRequest::get(array('mailSend', 'mailBody'));
					if (!empty($send)) {
						include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = "forum@est-horizon.com";
						$mail->FromName = "Forum Est-Horizon";
						$mail->Subject = "Demande d'inscription acceptée";
						$mail->Body = "Bonjour,<br /><br />

Votre demande d'inscription au Forum Est-Horizon a été validée.<br />
Vous pouvez dès à présent accéder à votre espace personnel avec les identifiants (e-mail) et mot de passe que vous avez fournis.<br /><br />
Dans l'onglet \"Vos outils\", il est alors possible de sélectionner votre pack et de remplir la fiche signalétique de votre entreprise qui figurera dans la Brochure Visiteurs. Le devis vous sera envoyé lorsque vous aurez réservé votre stand.<br /><br />

Nous vous remercions pour votre pré-inscription et restons à votre disposition pour tout renseignement complémentaire.<br /><br />

Cordialement,<br /><br />
<strong>Le Forum Est-Horizon</strong>";
						$mail->IsHTML(true);
						$mail->AddAddress($dbData['email']);
						$mail->Send();
					}
					
					WNote::success("Contact accepté", "Le contact <strong>".$contact['nom']."</strong> a été accepté avec succès.", 'session');
					// Redirection vers la fiche entreprise
					header('location: '.WRoute::getDir().'admin/entreprise/fiche/'.$contact['firmid']);
					// Historique
					$this->model->history($contact['firmid'], "Contact accepté", $cid);
					$this->model->register($contact['firmid'], $cid);
				}
			}
		} else {
			$this->view->contact_treat($cid);
			$this->render('contact_treat');
		}
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
	
	/**
	 * Refuse d'un contact
	 */
	protected function contact_refuse() {
		$cid = $this->getId();
		if ($this->model->validContactId($cid)) {
			if (empty($_POST)) {
				$this->view->contact_refuse($cid);
				$this->render('contact_refuse');
			} else {
				$contactData = $this->model->loadContact($cid);
				
				// Suppression du compte utilisateur
				if (!empty($contactData['userid'])) {
					include APP_DIR.'user/admin/model.php';
					$userModel = new UserAdminModel();
					$userModel->deleteUser(intval($contactData['userid']));
				}
				
				// Suppression du contact
				$this->model->deleteContact($cid);
				
/*				list($send, $email) = WRequest::get(array('send', 'email'));
 				// Envoi de l'email
				if (!empty($send)) {
					include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
					$mail = new PHPMailer();
					$mail->CharSet = 'utf-8';
					$mail->From = "forum@est-horizon.com";
					$mail->FromName = "Forum Est-Horizon";
					$mail->Subject = "Demande d'inscription refusée";
					$mail->Body = "Bonjour,<br /><br />

Nous avons le regret de vous informer que votre demande d'inscription au Forum Est-Horizon a été refusée.<br /><br />

Pour obtenir de plus amples informations, vous pouvez contacter les organisateurs par email à cette adresse :forum@est-horizon.com<br /><br />

Nous vous prions de croire en l'expression de nos sincères salutations.<br /><br />
<strong>Le Forum Est-Horizon</strong>";
					$mail->IsHTML(true);
					$mail->AddAddress($contactData['email']);
					$mail->Send();
				} */
				
				// Message
				WNote::success("Demande refusée", "La demande de ".$contactData['civilite']." ".$contactData['nom']." ".$contactData['prenom']." a été refusée avec succès.", 'session');
				header('location: '.WRoute::getDir().'admin/entreprise/waiting/');
				// Historique
				$this->model->history(0, "Contact refusé", $cid);
			}
		} else {
			WNote::error("Contact inexistant", "Le contact demandé est introuvable dans la base de données.", 'session');
			header('location: '.WRoute::getDir().'admin/entreprise/contact_waiting/');
		}
	}
	
	/**
	 * Gestion des catégories
	 */
	protected function cat() {
		/**
		 * Formulaire pour l'AJOUT d'une catégorie
		 */
		$name = WRequest::get('name', null, 'POST');
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!is_null($name)) {
			$erreurs = array();
			
			if (empty($name)) {
				$erreurs[] = "Il manque un nom à la catégorie.";
			}
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->createCat($name)) {
					WNote::success("Catégorie ajoutée", "La catégorie <strong>".$name."</strong> a été ajoutée avec succès.", 'assign');
				} else {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		/**
		 * Formulaire pour l'EDITION d'une catégorie
		 */
		$data = WRequest::getAssoc(array('idEdit', 'nameEdit'));
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$id = intval($data['idEdit']);
			unset($data['idEdit']);
			$erreurs = array();
			
			if (empty($data['nameEdit'])) {
				$erreurs[] = "Le nom de la catégorie est vide.";
			}
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->updateCat($id, $data['nameEdit'])) {
					WNote::success("Catégorie éditée", "La catégorie <strong>".$data['nameEdit']."</strong> a été éditée avec succès.", 'assign');
				} else {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		// Les notes
		WNote::treatNoteSession();
		
		$this->view->cat();
		$this->render('cat');
	}
	
	protected function cat_del() {
		$id = $this->getId();
		$this->model->deleteCat($id);
		WNote::success("Suppression d'une catégorie", "La catégorie a été supprimée avec succès.", 'session');
		header('location: '.WRoute::getDir().'admin/entreprise/cat/');
	}
}

?>
