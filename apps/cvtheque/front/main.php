<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/cvtheque/front/main.php 0001 14-05-2011 Fofif $
 */

class CvthequeController extends WController {
	
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new CvthequeModel();
		
		include 'view.php';
		$this->setView(new CvthequeView($this->model));
	}
	
	public function launch() {
		// Les notes
		WNote::treatNoteSession();
		
		$action = $this->getAskedAction();
		$this->forward($action, 'inscription');
	}
	
	public function inscription() {
        WNote::info("Inscription fermée", "Les inscriptions par internet au pôle Conseils sont désormais closes.<br />
		Cependant, vous pouvez toujours y participer en vous rendant directement à l'espace conseils du Forum Est-Horizon.", 'display');
		return;
		
		$already = false;
        $dontCreateUser = false;
		
        if($this->model->alreadyBooked()) {
            // Le compte user a déjà une réservation
			$data = WRequest::get(array('lastname', 'firstname', 'mail', 'ecole', 'diplome', 'secteur', 'recherche'), null, 'POST', false);
            $already = true;
            $dontCreateUser = true;
			$pass_save = '*********';
        } else {
            $data = WRequest::get(array('lastname', 'firstname', 'mail', 'password', 'ecole', 'diplome', 'secteur', 'recherche'), null, 'POST', false);
        }
		
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			// Traitement des variables
			$data['lastname'] = ucwords(strtolower($data['lastname']));
			$data['firstname'] = ucwords(strtolower($data['firstname']));
			
			// Traitement du compte utilisateur		
            include_once APP_DIR.'user/front/model.php';
            $userModel = new UserModel();
			
			if (empty($data['lastname'])) {
				$erreurs[] = "Veuillez renseigner votre nom de famille.";
			}
			if (empty($data['firstname'])) {
				$erreurs[] = "Veuillez renseigner votre prénom.";
			}
			// Si ecole==autre
			if ($data['ecole'] == 'autre') {
				$autre = WRequest::get('autre');
				if (empty($autre)) {
					$erreurs[] = "Veuillez spécifier votre autre école";
				} else {
					$data['ecole'] = $autre;
				}
			}
			if (empty($data['secteur'])) {
				$erreurs[] = "Veuillez spécifier un secteur de travail qui vous intéresse.";
			}
			
			// Si on a pas déjà booké
            if(!$already) {
				// Passwords identiques
			    if (empty($data['password'])) {
				    $erreurs[] = "Veuillez fournir un mot de passe de connexion.";
			    } else {
					$pass_save = $data['password'];
					unset($data['password']);
				}
				
				$dontCreateUser = false;
				$data['mail'] = strtolower($data['mail']);
			    if (empty($data['mail']) || !preg_match('#^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$#i', $data['mail'])) {
				    $erreurs[] = "Vous devez fournir une adresse email valide.";
			    } else if (!$userModel->emailAvailable($data['mail'])) {
                    // Regarde s'il existe déjà un compte user coïncidant
					$userMatching = $userModel->matchUser($data['mail'],sha1($pass_save),0);
					if($userMatching == array()) {
                        $erreurs[] = "Cet email est déjà pris.";
                    } else {
                        $dontCreateUser = true;
                    }
			    }
            }
			
			if (empty($_FILES['cv']['name']) && !$already) {
				$erreurs[] = "Vous avez oublié de fournir votre CV.";
			}
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
                if($already) {
                    $this->view->edit($data);
                    $this->render('edit');
                } else {
                    $this->view->inscription($data);
                    $this->render('inscription');
                }
			} else {
                // Traitement du compte utilisateur
				$valid = false;
                if($dontCreateUser) {
                    // Update de compte utilisateur
				    $userData = array(
                        'firstname' => $data['firstname'],
                        'lastname' => $data['lastname']
				    );
                    $valid = $userModel->updateUser($userMatching['id'], $userData);
                } else {
                    // Création de compte utilisateur
				    $userData = array(
					    'nickname' => $data['mail'],
					    'pass' => sha1($pass_save),
					    'email' => $data['mail'],
                        'firstname' => $data['firstname'],
                        'lastname' => $data['lastname'],
					    'access' => '',
					    'groupe' => 9 // ATTENTION : ID DE LA CAT ELEVE
				    );
                    $valid = $userModel->createUser($userData);
                }
				
				if (!$valid) {
					WNote::error("Erreur création compte", "Votre compte utilisateur n'a pas été correctement créé.<br />Veuillez refaire un essai.", 'display');
				} else {
                    if($dontCreateUser) {
                        $data['userid'] = $userMatching['id'];
                    } else {
                        $data['userid'] = $userModel->getLastUserId();
                    }
					
					// Détermination du créneau + intervenant
					$timeslot = WRequest::get('timeslot');
					if (WRequest::get('noconseil') == 'on' || is_null($timeslot)) {
						$data['interid'] = '';
						$data['schedid'] = '';
					} else {
						list($data['interid'], $data['schedid']) = explode('-', $timeslot);
					}
					
                    // Upload du CV
					$data['cv'] = '';
					$data['lettre'] = '';
					if (!empty($_FILES['cv']['name'])) {
						include HELPERS_DIR.'upload/upload.php';
						$upload = new Upload($_FILES['cv']);
						$upload->file_new_name_body = (string) $data['userid'].'_'.urlencode($data['lastname'].'_'.$data['firstname']);
						$upload->file_overwrite = true;
						$upload->Process(WT_PATH.'upload/CV/2011/');
						if (!$upload->processed) {
							WNote::error("Erreur lors de l'upload", "Erreur lors de l'upload du pdf : ".$upload->error, 'display');
							return ;
						} else {
							$data['cv'] = $upload->file_dst_name;
						}
					}
					if (!empty($_FILES['lettre']['name'])) {
						$upload = new Upload($_FILES['lettre']);
						$upload->file_new_name_body = (string) $data['userid'].'_'.urlencode($data['lastname'].'_'.$data['firstname']).'_lettre_motiv';
						$upload->file_overwrite = true;
						$upload->Process(WT_PATH.'upload/CV/2011/');
						if (!$upload->processed) {
							WNote::error("Erreur lors de l'upload", "Erreur lors de l'upload du pdf : ".$upload->error, 'display');
							return;
						} else {
							$data['lettre'] = $upload->file_dst_name;
						}
					}

					// Ajout du créneau dans la bdd
					if (!$this->model->bookIt($data)) {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'display');
					} else {
						WNote::success("Inscription réussie", "Vous venez de recevoir un courriel à l'adresse email que vous nous avez fournie.", 'display');
						
						$message = "Bonjour,<br /><br />
Vous venez de vous inscrire sur le site du Forum Est-Horizon ".date('Y', time()).".<br /><br />

Veuillez trouver ci-dessous vos données de connexion :<br />
Identifiant : ".$data['mail']."<br />
Password : ".$pass_save."<br /><br />";
						if (WRequest::get('noconseil') != 'on') {
							$interData = $this->model->getIntervenantData($data['interid']);
							$message .= "<u>Nous vous rappelons que vous vous êtes inscrit au pôle conseil avec ".$interData['name']." à ".$data['schedid'].".</u><br />
							Veuillez vous assurer de votre présence.<br /><br />";
						}
						$message .= "Merci pour votre inscription.<br /><br />

<strong>Le Forum Est-Horizon</strong>";
/*Vous pouvez consulter et modifier votre inscription sur <a href='http://www.est-horizon.com'>www.est-horizon.com</a>.<br /><br />*/
						
						// Envoi de l'email
						include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@mines.inpl-nancy.fr';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = 'Votre inscription au Pôle Conseil du Forum Est Horizon '.date('Y', time());
						$mail->Body = $message;
						$mail->IsHTML(true);
						$mail->AddAddress($data['mail']);
						$mail->Send();
					}
				}
			}
		} else {
            if($already) {
                $this->view->edit();
                $this->render('edit');
            } else {
                $this->view->inscription();
                $this->render('inscription');
            }
		}
	}
	
	public function entreprise() {
		if ((!empty($_SESSION['nickname']) && $_SESSION['groupe'] == 4) || !empty($_SESSION['accessString'])) {
			$filtre_ecole = WRequest::get('ecole');
			$filtre_recherche = WRequest::get('recherche');
			$this->view->entreprise($filtre_ecole, $filtre_recherche);
			$this->render('entreprise');
		} else {
			WNote::error("Accès interdit", "Vous devez être connecté pour accéder à cette zone.", 'display');
		}
	}

    private function getId($argN = 0) {
		$args = WRoute::getArgs();
		if (empty($args[0])) {
			return 0;
		} else {
			return $args[$argN];
		}
	}

    public function getCV() {
		$userid = $this->getId(1);
        if ((!empty($_SESSION['nickname']) && $_SESSION['groupe'] == 4) || !empty($_SESSION['accessString'])) {
            if(file_exists(WT_PATH."upload/CV/2011/".$userid)) {
                $filename = WT_PATH."upload/CV/2011/".$userid;
		        header('Content-Description: File Transfer');
		        header('Content-Type: application/octet-stream');
		        header('Content-Disposition: attachment; filename="'. basename($filename) .'";');
		        @readfile($filename) OR die();
            } else {
                WNote::error("Aucun CV", "Pas de CV correspondant.", 'display');
            }
        } else {
			WNote::error("Accès interdit", "Vous devez être connecté pour accéder à cette zone.", 'display');
		}
	}
}

?>