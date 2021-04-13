<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif
 * @version	$Id: entreprise/admin/main.php 0002 10-04-2011 Fofif $
 */

class LogisticAdminController extends WController {
    /*
     * Les opérations du module
     */
    protected $actionList = array(
                        'firm' => "Entreprises",
                        'type_stands' => "Types de stands",
							'del_stand' => "\\",
							'type_options' => "Types d'options",
                        'del_option' => "\\",
                        'type_lots' => "Types de lots",
							'del_lot' => "\\",
                        'type_accessoires' => "Types d'accessoires",
							'del_accessoire' => "\\",
							'type_reduc' => "Réductions",
							'del_reduc' => "\\",
						'firm_logistic' => "Liste Logistique"
                    );
    private $db;

    public function __construct() {
        // Chargement des modèles
        include 'model.php';
        $this->model = new LogisticAdminModel();

        include 'view.php';
        $this->setView(new LogisticAdminView($this->model));
    }

    public function launch() {
        $action = $this->getAskedAction();
        $this->db = WSystem::getDB();
        $this->forward($action, 'firm');        
    }
		
	private function ucname($string) {
		$string =ucwords(strtolower($string));

		foreach (array('-', '\'','#') as $delimiter) {
		  if (strpos($string, $delimiter)!==false) {
			$string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
		  }
		}
		return $string;
	}

	
	/*
	 * Affichage des stands
	 */
    public function type_stands() {
		 /**
		 * Formulaire pour l'AJOUT d'un type de stand
		 */
		$data = WRequest::get(array('taille','prix', 'nbstand'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			
			if (empty($data['taille']) || intval($data['taille'])==0) {
				$erreurs[] = "Il manque une taille au stand ou cette taille n'est pas valide.";
			}
			if (empty($data['prix']) || intval($data['prix'])==0) {
				$erreurs[] = "Il manque un prix au stand ou ce prix n'est pas valide.";
			}
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->add_Stand($data['prix'],$data['taille'],$data['nbstand'])) {
					WNote::success("Stand ajouté", "Le stand de <strong>".$data['taille']." m²</strong> a été ajoutée avec succès.", 'assign');
				} else {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		/**
		 * Formulaire pour l'EDITION d'un type de stand
		 */
		$data = WRequest::get(array('idEdit', 'tailleEdit', 'prixEdit', 'nbstandEdit'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			if (empty($data['tailleEdit']) || intval($data['tailleEdit'])==0) {
				$erreurs[] = "Il manque une taille au stand ou cette taille n'est pas valide.";
			}
			if (empty($data['prixEdit']) || intval($data['prixEdit'])==0) {
				$erreurs[] = "Il manque un prix au stand ou ce prix n'est pas valide.";
			}
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->update_Stand($data['idEdit'],$data['prixEdit'],$data['tailleEdit'], $data['nbstandEdit'])) {
					WNote::success("Stand édité", "Le stand de <strong>".$data['tailleEdit']." m²</strong> a été modifié avec succès.", 'assign');
				} else {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
				}
			}		
		}
		
		$this->view->type_stands();
        $this->render('type_stands'); 		
      }
	
	/*
	 * Suppression des stands
	 */
	public function del_stand() {
		$args = WRoute::getArgs();
		if(!empty($args[1])) {
			if($this->model->delete_Stand($args[1])) {
				WNote::success("Stand supprimé", "Le stand a été supprimé avec succès.", 'assign');
			} else {
				WNote::error("Erreur lors de la suppression", "Une erreur inconnue s'est produite.", 'assign');
			}
		}
		
		$this->view->type_stands();	
		$this->render('type_stands'); 		
	}
	
	/*
	 * Affichage des lots
	 */
    public function type_lots() {
		
		/**
		 * Formulaire pour l'AJOUT ou l'EDITION d'un type de lot
		 */
		$data = WRequest::get(array('nom','prix','description','chaise_nom','chaise_prix','chaise_number','tabouret_nom','tabouret_prix','tabouret_number','table_nom','table_prix','table_number','banque_nom','banque_prix','banque_number','presentoir_nom','presentoir_prix','presentoir_number','color','accessoire_spec','idEdit'), null, 'POST', false);
		$args = WRoute::getArgs();
		$edit = false;

		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			/*
			 * TRAITEMENT DES VARIABLES
			 */
			//Variable nom
			if (isset($data['nom']) && empty($data['nom'])) {
				$erreurs[] = "Il manque une nom au lot.";
			} else if (!$this->model->lotNameAvailable($this->ucname($data['nom'])) && $data['idEdit'] == 'false') {
				$erreurs[] = "Un lot avec ce nom existe déjà.";
			}
			$data['nom'] = $this->ucname($data['nom']);
			
			//Variable prix
			if (empty($data['prix'])) {
				$erreurs[] = "Il manque un prix au stand ou ce prix n'est pas valide.";
			} else if(intval($data['prix'])==0) {
				$erreurs[] = "Ce prix n'est pas valide.";
			} 
			
			//Variable description
			if (empty($data['description'])) {
				$erreurs[] = "Il manque une description.";
			}
			
			//Variable color
			if (empty($data['color'])) {
				$erreurs[] = "Il manque une couleur.";
			}
			$data['color'] = $this->ucname($data['color']);
	
			/*
			 * TRAITEMENT DES VARIABLES ELEMENTS
			 * On verifie qu'elles ne sont pas vides et on 'inverse' les tableaux
			 */
			$elements = Array(
				'chaise' => 'chaise',
				'tabouret' => 'tabouret',
				'table' => 'table',
				'presentoir' => 'présentoir',
				'banque' => 'banque'
			);
			
			foreach($elements as $key => $name) {
				if (empty($data[$key.'_nom']) || empty($data[$key.'_nom'][0])) {
					//$erreurs[] = "Il manque le nom à un(e) $name.";
					//Pas d'élément de ce type on continue pour voir
					${$key} = Array();
				} else {
					foreach($data[$key.'_nom'] as $key2 => $data2) {
						if(empty($data[$key.'_nom'][$key2])) {
							$erreurs[] = "Il manque le nom à un(e) $name.";
						} else {
							${$key}[$key2]['nom'] = $this->ucname($data[$key.'_nom'][$key2]);
						}
						if(empty($data[$key.'_number'][$key2])) {
							$erreurs[] = "Il manque un nombre de $name.";							
						} else {
							${$key}[$key2]['nombre'] = $data[$key.'_number'][$key2];
						}
						if(empty($data[$key.'_prix'][$key2])) {
							$erreurs[] = "Il manque le prix à un(e) $name.";
						} else {
							${$key}[$key2]['prix'] = $data[$key.'_prix'][$key2];
						}
					}
				}
			}
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
				$this->view->type_lots($data);				
			} else {
				
				if($data['idEdit'] == 'false') {
					if ($this->model->add_Lot(intval($data['prix']),$data['nom'],$data['description'],$chaise,$table,$tabouret,$banque,$presentoir,$data['color'],($data['accessoire_spec'][0] != 0) ? $data['accessoire_spec'] : array())) {
						WNote::success("Lot ajouté", "Le lot <strong>".$data['nom']."</strong> a été ajoutée avec succès.", 'assign');
						
					} else {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					}					
				} else {
					if ($this->model->update_Lot($data['idEdit'],intval($data['prix']),$data['nom'],$data['description'],$chaise,$table,$tabouret,$banque,$presentoir,$data['color'],($data['accessoire_spec'][0] != 0) ? $data['accessoire_spec'] : array())) {
						WNote::success("Lot édité", "Le lot <strong>".$data['nom']."</strong> a été modifié avec succès.", 'assign');
						$data = array();
					} else {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					}				
				}
			}			
		} else if(isset($args[1]) && intval($args[2]) != 0) {
			/*
			 * Si on est en mode EDITION on passe les données
			 **/
			$data = $this->model->get_Lot($args[2]);
			$elements = Array(
				'chaise',
				'tabouret',
				'table',
				'presentoir',
				'banque'
			);
			foreach($elements as $element) {
				foreach($data[$element] as $key => $data2) {
					$data[$element.'_nom'][$key] = $data[$element][$key]['nom'];
					$data[$element.'_nombre'][$key] = $data[$element][$key]['nombre'];
					$data[$element.'_prix'][$key] = $data[$element][$key]['prix'];
				}
			}
			$data['color'] = implode('#',$data['color']);
			$edit = $args[2];
		} else {
			$data = array();
		}
		
		$this->view->type_lots($data,$edit);
        $this->render('type_lots'); 
    }
	
	/*
	 * Suppression des lots
	 */
	public function del_lot() {
		$args = WRoute::getArgs();
		if(!empty($args[1])) {
			if($this->model->delete_Lot($args[1])) {
				WNote::success("Lot supprimé", "Le lot a été supprimé avec succès.", 'assign');
			} else {
				WNote::error("Erreur lors de la suppression", "Une erreur inconnue s'est produite.", 'assign');
			}
		}
		$this->view->type_lots();
		$this->render('type_lots'); 
	}
	
	/*
	 * Affichage des accessoires
	 */
	public function type_accessoires() {
		 /**
		 * Formulaire pour l'AJOUT d'un type d'accessoires
		 */
		$data = WRequest::get(array('nom','prix','description','for_all'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			$data1 = WRequest::get(array('add_to_lot'), null, 'POST', false);
			$data['add_to_lot'] = $data1['add_to_lot'];
			
			/*
			 * VERIFICATION DES DONNEES
			 */
			if (empty($data['nom'])) {
				$erreurs[] = "Il manque un nom à l'accessoire.";
			} else if (!$this->model->accessoireNameAvailable($data['nom'])) {
				$erreurs[] = "Un accessoire avec ce nom existe déjà.";
			}
			$data['nom'] = $this->ucname($data['nom']);
			
			if (empty($data['prix']) || intval($data['prix'])==0) {
				$erreurs[] = "Il manque un prix à l'accessoire ou ce prix n'est pas valide.";
			}
			if (empty($data['description'])) {
				$erreurs[] = "Il manque une description à l'accessoire.";
			}
			
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if($data['for_all']) {
					if ($this->model->add_Accessoire($data['prix'],$data['nom'],$data['description'])) {
						WNote::success("Accessoire ajouté", "L'accessoire <strong>".$data['nom']."</strong> a été ajouté avec succès.", 'assign');
						$data = array();
					} else {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					}
				} else {
					if ($this->model->add_Accessoire($data['prix'],$data['nom'],$data['description'],$data['add_to_lot'])) {
						WNote::success("Accessoire ajouté", "L'accessoire <strong>".$data['nom']."</strong> a été ajouté avec succès.", 'assign');
						$data = array();
					} else {
						WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
					}			
				}				
			}			
		} else {			
			$data = array();
		}
		
		/*
		 * Formulaire pour l'EDITION des accessoires
		 */
		$data = WRequest::get(array('idEdit','nomEdit','prixEdit','descriptionEdit','for_allEdit'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			$data1 = WRequest::get(array('add_to_lotEdit'), null, 'POST', false);
			$data['add_to_lotEdit'] = $data1['add_to_lotEdit'];
			/*
			 * VERIFICATION DES DONNEES
			 */
			if (empty($data['nomEdit'])) {
				$erreurs[] = "Il manque un nom à l'accessoire.";
			}
			$data['nomEdit'] = $this->ucname($data['nomEdit']);
			
			if (empty($data['prixEdit']) || intval($data['prixEdit'])==0) {
				$erreurs[] = "Il manque un prix à l'accessoire ou ce prix n'est pas valide.";
			}
			if (empty($data['descriptionEdit'])) {
				$erreurs[] = "Il manque une description à l'accessoire.";
			}
			
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if($data['for_allEdit']) {
					if ($this->model->update_Accessoire($data['idEdit'],$data['prixEdit'],$data['nomEdit'],$data['descriptionEdit'])) {
						WNote::success("Accessoire édité", "L'accessoire <strong>".$data['nomEdit']."</strong> a été édité avec succès.", 'assign');
						$data = array();
					} else {
						WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
					}
				} else {
					if ($this->model->update_Accessoire($data['idEdit'],$data['prixEdit'],$data['nomEdit'],$data['descriptionEdit'],$data['add_to_lotEdit'])) {
						WNote::success("Accessoire modifié", "L'accessoire <strong>".$data['nomEdit']."</strong> a été modifié avec succès.", 'assign');
						$data = array();
					} else {
						WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
					}			
				}				
			}			
		}
		
		$this->view->type_accessoires($data); 
        $this->render('type_accessoires'); 
     }
	
	/*
	 * Suppression d'un accessoire
	 */
	public function del_accessoire() {
		$args = WRoute::getArgs();
		if(!empty($args[1])) {
			if($this->model->delete_Accessoire($args[1])) {
				WNote::success("Accessoire supprimé", "L'accessoire a été supprimée avec succès.", 'assign');
			} else {
				WNote::error("Erreur lors de la suppression", "Une erreur inconnue s'est produite.", 'assign');
			}
		}		
		$this->view->type_accessoires();
        $this->render('type_accessoires'); 	
	}
	/*
	 * Affichage des options
	 */
	public function type_options() {
		 /**
		 * Formulaire pour l'AJOUT d'un type d'option
		 */
		$data = WRequest::get(array('nom','prix','categorie','countable','description'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			
			/*
			 * VERIFICATION DES DONNEES
			 */
			if (empty($data['nom'])) {
				$erreurs[] = "Il manque un nom à l'option.";
			} else if (!$this->model->optionNameAvailable($data['nom'])) {
				$erreurs[] = "Une option avec ce nom existe déjà.";
			}
			if (empty($data['prix']) || intval($data['prix'])<0) {
				$erreurs[] = "Il manque un prix à l'option ou ce prix n'est pas valide.";
			}
			if (empty($data['categorie']) || intval($data['categorie'])==0) {
				$erreurs[] = "Il manque une catégorie à l'option ou cette catégorie n'est pas valide.";
			}
			if (empty($data['countable']) || intval($data['countable'])==0) {
				$erreurs[] = "Il manque un type à l'option ou ce type n'est pas valide.";
			}
			if (empty($data['description'])) {
				$erreurs[] = "Il manque une description à l'option.";
			}
			
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->add_Option($data['prix'],$data['nom'],$data['categorie']-1,$data['countable']-1,$data['description'])) {
					WNote::success("Option ajoutée", "L'option <strong>".$data['nom']."</strong> a été ajoutée avec succès.", 'assign');
					$data = array();
				} else {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		} else {			
			$data = array();
		}
		
		/**
		 * Formulaire pour l'EDITION d'un type d'option
		 */
		$data = WRequest::get(array('idEdit','nomEdit','prixEdit','categorieEdit','countableEdit','descriptionEdit'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			
			/*
			 * VERIFICATION DES DONNEES
			 */
			if (empty($data['nomEdit'])) {
				$erreurs[] = "Il manque un nom à l'option.";
			}
			if (empty($data['prixEdit']) || intval($data['prixEdit'])<0) {
				$erreurs[] = "Il manque un prix à l'option ou ce prix n'est pas valide.";
			}
			if (empty($data['categorieEdit']) || intval($data['categorieEdit'])==0) {
				$erreurs[] = "Il manque une catégorie à l'option ou cette catégorie n'est pas valide.";
			}
			if (empty($data['countableEdit']) || intval($data['countableEdit'])==0) {
				$erreurs[] = "Il manque un type à l'option ou ce type n'est pas valide.";
			}
			if (empty($data['descriptionEdit'])) {
				$erreurs[] = "Il manque une description à l'option.";
			}
			
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->update_Option($data['idEdit'],$data['prixEdit'],$data['nomEdit'],intval($data['categorieEdit'])-1,intval($data['countableEdit'])-1,$data['descriptionEdit'])) {
					WNote::success("Option éditée", "L'option <strong>".$data['nomEdit']."</strong> a été éditée avec succès.", 'assign');
					$data = array();
				} else {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		$this->view->type_options($data);
        $this->render('type_options'); 
    }
	
	/*
	 * DELETE OPTION
	 */
	public function del_option() {
		$args = WRoute::getArgs();
		if(!empty($args[1])) {
			if($this->model->delete_Option($args[1])) {
				WNote::success("Option supprimée", "L'option a été supprimée avec succès.", 'assign');
			} else {
				WNote::error("Erreur lors de la suppression", "Une erreur inconnue s'est produite.", 'assign');
			}
		}		
		$this->view->type_options();
        $this->render('type_options'); 
      }
	
	/*
	 * FIRM STAND
	 */
	public function firm_stand() {
		$args = WRoute::getArgs();
		$sortData = explode('-', @$args[1]);
		$sortBy = empty($sortData) ? '' : array_shift($sortData);
		$sens = empty($sortData) ? '' : array_shift($sortData);
		$page = empty($sortData) ? 1 : $sortData[0];
		
		// Les notes
		WNote::treatNoteSession();
		
		$this->view->firm_stand($sortBy, $sens, $page);
		$this->render('firm_stand');
	}
	
	/*
	 * Réductions
	 */
	public function type_reduc() {
		/**
		 * Formulaire pour l'AJOUT d'une catégorie de réduction
		 */
		$data = WRequest::get(array('nom','taux','applyTo'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			
			/*
			 * VERIFICATION DES DONNEES
			 */
			if (empty($data['nom'])) {
				$erreurs[] = "Il manque un nom à la réduction.";
			}
			if (empty($data['taux']) || intval($data['taux'])<=0) {
				$erreurs[] = "Il manque un taux de réduction.";
			}
			if (empty($data['applyTo'])) {
				$erreurs[] = "Il manque le champs d'application de la réduction.";
			}			
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->add_Reduc($data)) {
					WNote::success("Réduction ajoutée", "La réduction <strong>".$data['nom']."</strong> a été ajoutée avec succès.", 'assign');
					$data = array();
				} else {
					WNote::error("Erreur lors de l'ajout", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		} else {			
			$data = array();
		}
		
		/**
		 * Formulaire pour l'EDITION d'une réduction
		 */
		$data = WRequest::get(array('idEdit','nomEdit','tauxEdit','applyToEdit'), null, 'POST', false);
		// On vérifie que le formulaire a été envoyé par la non présence d'une valeur "null" cf WRequest
		if (!in_array(null,$data, true)) {
			$erreurs = array();
			
			/*
			 * VERIFICATION DES DONNEES
			 */
			if (empty($data['nomEdit'])) {
				$erreurs[] = "Il manque un nom à la réduction.";
			}
			if (empty($data['tauxEdit']) || intval($data['tauxEdit'])<=0) {
				$erreurs[] = "Il manque un taux de réduction.";
			}
			if (empty($data['applyToEdit'])) {
				$erreurs[] = "Il manque le champs d'application de la réduction.";
			}
			
			if (!empty($erreurs)) { // Il y a un problème
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'assign');
			} else {
				if ($this->model->update_Reduc($data)) {
					WNote::success("Réduction éditée", "La réduction <strong>".$data['nomEdit']."</strong> a été éditée avec succès.", 'assign');
					$data = array();
				} else {
					WNote::error("Erreur lors de l'édition", "Une erreur inconnue s'est produite.", 'assign');
				}
			}
		}
		
		$this->view->type_reducs($data);
        $this->render('type_reducs'); 
	}
	
	public function del_reduc() {
		$args = WRoute::getArgs();
		if(!empty($args[1])) {
			if($this->model->delete_Reduc($args[1])) {
				WNote::success("Réduction supprimée", "La réduction a été supprimée avec succès.", 'assign');
			} else {
				WNote::error("Erreur lors de la suppression", "Une erreur inconnue s'est produite.", 'assign');
			}
		}		
		$this->view->type_reducs();
        $this->render('type_reducs');
	}
	
	public function firm() {
		$this->view->firm();
		$this->render('firm');	
	}

	public function firm_logistic() {
		$this->view->firm();
		$this->render('firm_logistic');	
	}
	
	public function facture() {
		$args = WRoute::getArgs();
		if(!empty($args[1])) {
			$firmid = intval($args[1]);
			$data = $this->model->get_facture($firmid);
			
			// Existance de l'entreprise
			if (!empty($data)) {
				$facture = WRequest::get(array('name', 'adress', 'city', 'postal_code', 'country', 'echeance', 'remise'), null, 'POST', false);
				$facture['ref'] = date('Y-',time()).sprintf("%04d", $firmid);
				
				// Dossier facturation
				$dir = WT_PATH.'private/factures/'.date('Y', time()).'/';
				if (!is_dir($dir)) {
					mkdir($dir);
				}
				$filename = $dir.$facture['ref'].'.pdf';
				
				// Le formulaire a-t-il été envoyé ?
				if (!in_array(null, $facture, true)) {
					$facture['date'] = date('d/m/y',time());
					$data['remise'] = $facture['remise'];
					
					include 'facture.php';
					$pdf = new PDF();
					$header = array(utf8_decode('Désignation'),'Prix Unitaire ('.chr(128).')',utf8_decode('Quantité'),'Total ('.chr(128).')');
					$pdf->SetFont('Arial','',14);
					$pdf->AddPage();
					$pdf->Head($facture);
					$pdf->DetailFactTable($header, $data);
					
					$pdf->Output($filename, 'F');
					
					$this->get_facture($firmid);
				} else {
					if (file_exists($filename)) {
						WNote::info("Facture déjà générée", "La facture de cette entreprise a déjà été générée.<br />
							Vous pouvez la télécharger à <a href=\"/admin/logistic/get_facture/".$firmid."\">cette adresse</a>.",
							'assign');
					}
					$this->view->facture($firmid);
					$this->render('facture');
				}
			}
		} else {
			header('location: '.WRoute::getDir().'admin/logistic/');
		}
	}
	
	public function get_facture($firmid = 0) {
		if (empty($firmid)) {
			$args = WRoute::getArgs();
			if(!empty($args[1])) {
				$firmid = intval($args[1]);
			} else {
				return;
			}
		}
		
		$ref = date('Y-',time()).sprintf("%04d", $firmid);
		$filename = WT_PATH.'private/factures/'.date('Y', time()).'/'.$ref.'.pdf';
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'. basename($filename) .'";');
		@readfile($filename) OR die();
	}
}

?>
