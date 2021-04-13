<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 * 
 * @author Fofif
 * @version	$Id: apps/news/admin/main.php 0002 01-08-2011 Fofif $
 */

class NewsAdminController extends WController {
	protected $actionList = array(
		'index' => "Liste des articles",
		'add' => "Ajouter un article",
		'edit' => "\Edition d'une news",
		'del' => "\Suppression d'une news",
		'cat' => "Gestion des catégories"
	);
	
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new NewsAdminModel();
		
		include 'view.php';
		$this->setView(new NewsAdminView($this->model));
	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'index');
	}
	
	/**
	 * Récupère un id fourni dans l'url
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
	
	protected function index() {
		$args = WRoute::getArgs();
		$sortData = explode('-', array_shift($args));
		if (empty($sortData)) {
			$sortBy = '';
			$sens = '';
		} else {
			$sortBy = array_shift($sortData);
			$sens = !empty($sortData) ? $sortData[0] : '';
		}
		
		// Les notes
		WNote::treatNoteSession();
		
		$this->view->index($sortBy, $sens);
		$this->render('index');
	}
	
	/*
	 * Ajout d'une news
	 */
	protected function add() {
		$data = WRequest::getAssoc(array('nAuthor', 'nKeywords', 'nTitle', 'nUrl', 'nContent', 'nCat'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			/**
			 * VERIFICATIONS
			 */
			if (empty($data['nTitle'])) {
				$erreurs[] = "Il manque un titre à l'article.";
			}
			
			if (empty($data['nAuthor'])) {
				$erreurs[] = "Il manque un auteur à l'article.";
			}
			
			// Traitement du permalien
			$data['nUrl'] = strtolower($data['nUrl']);
			$data['nUrl'] = preg_replace('#[^a-z0-9.]#', '-', $data['nUrl']);
			$data['nUrl'] = preg_replace('#-{2,}#', '-', $data['nUrl']);
			$data['nUrl'] = trim($data['nUrl'], '-');
			
			// Traitement l'image à la une
			if (!empty($_FILES['image']['name'])) {
				include HELPERS_DIR.'upload/upload.php';
				$upload = new Upload($_FILES['image']);
				$upload->file_new_name_body = preg_replace('#[^a-z0-9]#', '', strtolower($data['nTitle']));
				$upload->file_overwrite = true;
				$upload->Process(WT_PATH.'upload/news/');
				if (!$upload->processed) {
					$erreurs[] = "Erreur lors de l'upload de l'image à la une : ".$upload->error;
				}
				$data['nImage'] = $upload->file_dst_name;
			} else {
				$data['nImage'] = '';
			}
			/**
			 * FIN VERIFICATIONS
			 */
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->add($data);
				$this->render('add');
			} else {
				// Mise à jour des infos
				if ($this->model->createNews($data)) {
					// Traitement des catégories
					if (!empty($data['nCat'])) {
						$nid = $this->model->getLastNewsId();
						// Récupération des id des catégories stockées dans les clés du tableau
						foreach ($data['nCat'] as $cid => $v) {
							$this->model->newsAddCat($nid, $cid);
						}
					}
					
					WNote::success("Article ajouté", "L'article <strong>".$data['nTitle']."</strong> a été créé avec succès.", 'session');
					header('location: '.WRoute::getDir().'admin/news/');
				} else {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					$this->view->add($data);
					$this->render('add');
				}
			}
		} else {
			$this->view->add();
			$this->render('add');
		}
	}
	
	protected function edit() {
		$id = $this->getId();
		
		// Vérification de la validité de l'id
		if (!$this->model->validId($id)) {
			WNote::error("News introuvable", "L'article que vous tentez de modifier n'existe pas.", 'session');
			header('location: '.WRoute::getDir().'admin/news/');
			return;
		}
		
		$data = WRequest::getAssoc(array('nAuthor', 'nKeywords', 'nTitle', 'nUrl', 'nContent', 'nCat'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			// Récupération des données liées à cette page pour comparaison
			$realData = $this->model->loadNews($id);
			
			/**
			 * VERIFICATIONS
			 */
			if (empty($data['nTitle'])) {
				$erreurs[] = "Il manque un titre à l'article.";
			}
			
			if (empty($data['nAuthor'])) {
				$erreurs[] = "Il manque un auteur à l'article.";
			}
			
			if (empty($data['nUrl'])) {
				$erreurs[] = "Aucun permalien (lien vers la news) n'a été défini.";
			} else {
				// Traitement du permalien pour assurer
				$data['nUrl'] = strtolower($data['nUrl']);
				$data['nUrl'] = preg_replace('#[^a-z0-9.]#', '-', $data['nUrl']);
				$data['nUrl'] = preg_replace('#-{2,}#', '-', $data['nUrl']);
				$data['nUrl'] = trim($data['nUrl'], '-');
			}
			
			// Traitement l'image à la une
			if (!empty($_FILES['image']['name'])) {
				include HELPERS_DIR.'upload/upload.php';
				$upload = new Upload($_FILES['image']);
				$upload->file_new_name_body = preg_replace('#[^a-z0-9]#', '', strtolower($data['nTitle']));
				$upload->file_overwrite = true;
				$upload->Process(WT_PATH.'upload/news/');
				if (!$upload->processed) {
					$erreurs[] = "Erreur lors de l'upload de l'image à la une : ".$upload->error;
				}
				$data['nImage'] = $upload->file_dst_name;
			}
			
			// Traitement des catégories
			$this->model->newsDestroyCats($id);
			if (!empty($data['nCat'])) {
				// Récupération des id des catégories stockées dans les clés du tableau
				foreach ($data['nCat'] as $cid => $v) {
					$this->model->newsAddCat($id, $cid);
				}
			}
			unset($data['nCat']);
			
			// Ajout du nom de l'éditeur
			$data['nEditor_id'] = $_SESSION['nickname'];
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->edit($id, $data);
				$this->render('edit');
			} else {
				// Mise à jour des infos
				if ($this->model->updateNews($id, $data)) {
					WNote::success("News éditée", "L'article <strong>".$data['nTitle']."</strong> a été modifié avec succès.", 'session');
					header('location: '.WRoute::getDir().'admin/news/');
				} else {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
					$this->view->edit($id, $data);
					$this->render('edit');
				}
			}
		} else {
			$this->view->edit($id);
			$this->render('edit');
		}
	}
	
	protected function del() {
		$id = $this->getId();
		if ($this->model->validId($id)) {
			if (WRequest::get('confirm', null, 'POST') === '1') {
				$data = $this->model->loadNews($id);
				$this->model->deleteNews($id);
				$this->model->newsDestroyCats($id);
				WNote::success("Suppression d'un article", "L'article \"<strong>".$data['title']."</strong>\" a été supprimé avec succès.", 'session');
				header('location: '.WRoute::getDir().'admin/news/');
			} else {
				$this->view->del($id);
				$this->render('del');
			}
		} else {
			WNote::error("Article introuvable", "L'article que vous tentez de supprimer n'existe pas.", 'session');
			header('location: '.WRoute::getDir().'admin/news/');
		}
	}
	
	/**
	 * Gestion des catégories
	 */
	protected function cat() {
		// Préparation tri colonnes
		$args = WRoute::getArgs();
		if (isset($args[1])) {
			$sortData = explode('-', $args[1]);
		} else {
			$sortData = array();
		}
		
		if (empty($sortData)) {
			$sortBy = '';
			$sens = '';
		} else {
			$sortBy = array_shift($sortData);
			$sens = !empty($sortData) ? $sortData[0] : '';
		}
		
		/**
		 * Formulaire pour l'AJOUT d'une catégorie
		 */
		$data = WRequest::getAssoc(array('cName', 'cShortname', 'cParent'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			if (empty($data['cName'])) {
				$erreurs[] = "Veuillez spécifier un nom pour la nouvelle catégorie.";
			}
			
			// Formatage du nom racourci
			if (empty($data['cShortname'])) {
				$data['cShortname'] = strtolower($data['cName']);
			} else {
				$data['cShortname'] = strtolower($data['cShortname']);
			}
			$data['cShortname'] = preg_replace('#[^a-z0-9.]#', '-', $data['cShortname']);
			$data['cShortname'] = preg_replace('#-{2,}#', '-', $data['cShortname']);
			$data['cShortname'] = trim($data['cShortname'], '-');
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->createCat($data)) {
					WNote::success("Catégorie ajoutée", "La catégorie <strong>".$data['cName']."</strong> a été ajoutée avec succès.", 'assign');
				} else {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		/**
		 * Formulaire pour l'EDITION d'une catégorie
		 */
		$data = WRequest::getAssoc(array('cIdEdit', 'cNameEdit', 'cShortnameEdit', 'cParentEdit'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			if (empty($data['cNameEdit'])) {
				$erreurs[] = "Le nom de la catégorie est vide.";
			}
			
			// Formatage du nom racourci
			if (empty($data['cShortnameEdit'])) {
				$data['cShortnameEdit'] = strtolower($data['cNameEdit']);
			} else {
				$data['cShortnameEdit'] = strtolower($data['cShortnameEdit']);
			}
			$data['cShortnameEdit'] = preg_replace('#[^a-z0-9.]#', '-', $data['cShortnameEdit']);
			$data['cShortnameEdit'] = preg_replace('#-{2,}#', '-', $data['cShortnameEdit']);
			$data['cShortnameEdit'] = trim($data['cShortnameEdit'], '-');
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->updateCat(intval($data['cIdEdit']), $data)) {
					WNote::success("Catégorie éditée", "La catégorie <strong>".$data['cNameEdit']."</strong> a été éditée avec succès.", 'assign');
				} else {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		// Les notes
		WNote::treatNoteSession();
		
		$this->view->cat($sortBy, $sens);
		$this->render('cat');
	}
	
	protected function cat_del() {
		$id = $this->getId();
		$this->model->deleteCat($id);
		WNote::success("Suppression d'une catégorie", "La catégorie a été supprimée avec succès.", 'session');
		header('location: '.WRoute::getDir().'admin/news/cat/');
	}
}

?>