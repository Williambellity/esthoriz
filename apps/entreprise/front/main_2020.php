<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/front/main.php 0001 14-05-2011 Fofif $
 */

class EntrepriseController extends WController {
	// Id de la catégorie user > entreprise
	const USER_CATID_FIRM = 4;
	
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new EntrepriseModel();
		
		include 'view.php';
		$this->setView(new EntrepriseView($this->model));
	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'toolbox');
	}
	
	/**
	 * Page d'accueil de l'espace privé des exposants
	 */
	public function toolbox() {
		if (!empty($_SESSION['nickname']) && $_SESSION['groupe'] == 4) {
			$this->view->assign('css', '/apps/entreprise/front/css/toolbox.css');
			$this->view->assign('css', '/apps/entreprise/front/css/style.css');
			$this->view->assign('pageTitle', 'Bienvenue sur votre espace personnel');
			
			// Chargement des news
			include APP_DIR.'news/front/model.php';
			$newsModel = new NewsModel();
			foreach ($newsModel->getNewsByCat(array('private'), 0, 3) as $news) {
				$this->view->tpl->assignBlockVars('news', $news);
			}
			
			// Traitement des applis
			$this->loadApplis();
			
			$this->render('toolbox');
		} else {
			WNote::error("Accès interdit", "Vous devez être connecté pour accéder à cette zone.", 'display');
		}
	}
	
	/**
	 * Chargement des applis + traitement
	 */
	public function loadApplis($getState = true) {
		$notifs = array();
		$i = 0;
		$toolbox = simplexml_load_file(APP_DIR.'entreprise/front/templates/toolbox.xml');
		// print_r($toolbox);
		foreach ($toolbox as $k => $v) {
			$this->view->tpl->assignBlockVars('applis', array(
				'name' => $v->name,
				'icone' => $v->icone,
				'href' => $v->href
				));
				
			
			// Le getState
			if ($getState && !empty($v->getState)) {
				list($file, $signature) = explode('|', $v->getState);
				list($modelName, $f) = explode('::', $signature);
				$file = trim($file, '/');
				$f = trim($f, '()');
				// Tentative d'inclusion si model introuvable
				if (!class_exists($modelName) && file_exists(WT_PATH.$file)) {
					include WT_PATH.$file;
				}
				
				if (class_exists($modelName)) {
					$model = new $modelName();
					$result = $model->$f();
					// La fonction getState doit retourner un array(int priorite, string message)
					if (!empty($result)) {
						list($priorite, $msg) = $result;
						$notifs[$priorite][] = $msg;
						$i++;
					}
				}
			}
		}
		
		if (!empty($notifs)) {
			ksort($notifs);
			foreach($notifs as $priority => $messages) {
				foreach ($messages as $m) {
					$this->view->tpl->assignBlockVars('notifs', array(
						'priority' => $priority,
						'message' => $m
					));
				}
			}
			$this->view->assign('notifs_count', $i);
			//WNote::info("Notifications", "<ul><li>".implode('</li><li>', $notifsFinal)."</li></ul>", 'assign');
			/*$this->view->assign('css', '/themes/system/styles/note.css');
			*/
		}
	}
	
	public function inscription() {
		$data = WRequest::get(array('firm', 'civi', 'name', 'fname', 'poste', 'address', 'tel', 'fax', 'mob', 'mail', 'password'), null, 'POST', false);
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			// Traitement des variables
			$data['name'] = ucwords(strtolower($data['name']));
			$data['fname'] = ucwords(strtolower($data['fname']));
			$data['tel'] = preg_replace('#[^0-9+()]#', '', $data['tel']);
			$data['fax'] = preg_replace('#[^0-9+()]#', '', $data['fax']);
			$data['mob'] = preg_replace('#[^0-9+()]#', '', $data['mob']);
			
			// Traitement du compte utilisateur
			include APP_DIR.'user/front/model.php';
			$userModel = new UserModel();
			
			if (empty($data['mail']) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
				$erreurs[] = "Vous devez fournir une adresse email valide.";
			}
			$data['mail'] = strtolower($data['mail']);
			
			// Passwords identiques
			if (!empty($data['password'])) {
				//if ($data['password'] === $data['password_conf']) {
					$pass_save = $data['password'];
					$password = sha1($data['password']);
					unset($data['password'], $data['password_conf']);
				//} else {
				//	$erreurs[] = "Les mots de passe que vous avez fournis sont différents.";
				//}
			} else {
				$erreurs[] = "Veuillez fournir un mot de passe de connexion.";
			}
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'display');
				//$this->view->contact_add($firmid, $data);
				//$this->render('contact_add');
			} else {
				// Création de compte utilisateur
				$userData = array(
					'nickname' => $data['mail'],
					'pass' => $password,
					'confirm' => 1,
					'email' => $data['mail'],
					'access' => '',
					'groupe' => self::USER_CATID_FIRM // ATTENTION : ID DE LA CAT ENTREPRISE
				);
				if (!$userModel->createUser($userData)) {
					WNote::error("Erreur création compte", "Votre compte utilisateur n'a pas été correctement créé.<br />Veuillez refaire un essai.", 'display');
				} else {
					$data['userid'] = $userModel->getLastUserId();
					// Ajout du contact dans la bdd
					if (!$this->model->createContact($data)) {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'display');
					} else {
						WNote::success("Inscription réussie", "Vous venez de recevoir un courriel à l'adresse email que vous nous avez fournie.<br />
							Veuillez suivre les instructions qu'il contient.", 'display');
						
						// Envoi de l'email
						include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = 'Confirmation de votre adresse email';
						$mail->Body = "Bonjour,<br /><br />
Vous venez de faire une demande d'inscription au Forum Est-Horizon ".date('Y', time()).".<br /><br />

Veuillez trouver ci-dessous vos données de connexion :<br />
Identifiant : ".$data['mail']."<br />
Password : ".$pass_save."<br /><br />

Pour finaliser votre demande, veuillez cliquer sur le lien ci-dessous :<br /><br />
<a href=\"http://www.est-horizon.com/entreprise/valider_demande/?email=".$data['mail']."\">Valider la demande</a><br /><br />

Si ce lien ne fonctionne pas, veuillez copier l'adresse suivante dans votre navigateur :<br />
http://www.est-horizon.com/entreprise/valider_demande/?email=".$data['mail']."<br /><br />

Merci pour votre inscription.<br /><br />

<strong>Le Forum Est-Horizon</strong>";
						$mail->IsHTML(true);
						$mail->AddAddress($data['mail']);
						$mail->Send();
					}
				}
			}
		} else {
			$this->view->inscription();
			$this->render('inscription');
		}
	}
	
	public function valider_demande() {
		$email = WRequest::get('email');
		if (!empty($email)) {
			$this->model->validateEmail($email);
			WNote::success("Demande validée", "Votre demande d'inscription au Forum Est-Horizon ".date('Y', time())." a bien été prise en compte.<br /><br />

Elle va maintenant être soumise à validation par l'équipe organisatrice.<br />
Vous serez prévenu ultérieurement par email de l'acceptation finale de votre inscription.<br /><br />

Nous vous remercions de votre confiance et de l'attention que vous portez à notre évènement.<br />
Nous restons à votre disposition pour de plus amples renseignements.<br /><br />

<strong>Le Forum Est-Horizon</strong>", 'display');
		} else {
			WNote::error("Erreur invalide", "L'email à valider est vide.", 'display');
		}
	}
}

?>