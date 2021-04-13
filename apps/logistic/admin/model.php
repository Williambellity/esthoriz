<?php
/**
* Wity CMS
* Système de gestion de contenu pour tous.
*
* @author	Fofif
* @version	$Id: entreprise/admin/model.php 0001 14-04-2011 Fofif $
*/
 
class LogisticAdminModel {
	private $db;
	
	public function __construct() {
		$this->db = WSystem::getDB();
	}
	
	/**
	 * Fonction implode_2D : transforme array($key=>array_data) en key1:'data1'-...-'datai',key2:data1-...-datai selon $model
	 * Arguments : arrays of array
	 * Retourne : $string
	*/	
	private function implode_2D($arrays) {
		$i = 0;
		$string = "";
		foreach($arrays as $key=>$data) {
			if($i > 0) {
				$string .= "#";
			}
			$str_ligne = "";
			$str_ligne .= implode("/",$data);			
			$string .= $str_ligne;
			$i++;
		}
		return $string;
	}
	
	/**
	 * Fonction inverse de implode_data, selon $model = Array($key_tt,$key1,$key2...)
	*/
	private function explode_2D($string,$model = null) {
		$data = explode('#',$string);
		if(!empty($string)) {
			$return = array();
			$i = 0;
			foreach($data as $row) {			
				$data_row = explode('/',$row);
				if($model != null) {
					$j = 1;
					foreach($data_row as $the_data) {
						$tmp[$model[$j]] = $the_data;
						$return[strtolower($tmp[$model[0]])] = $tmp;
						$j++;
					}
				} else {
					$return[$i] = $data_row;
				}
				$i++;
			}
			return $return;
		} else {
			return array();		
		}
	}
	/*
	 * FONCTION DE TRAITEMENT DES LOTS
	 */
	 
	/**
	 * Fonction ajouter lot
	 * Arguments : $chaise/$table... = array(array(nom,nombre,prix))
				$color = color#color
			    $accessoire_specific = array(id_accessoire)
	 * Retourne : true/false
	*/
	public function add_Lot($prix, $nom, $description, $chaise, $table, $tabouret, $banque, $presentoir, $color, $accessoire_specific = array()) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("INSERT INTO logistic_lot(prix,nom,description,set_chaise,set_table,set_tabouret,set_banque,set_presentoir,color,accessoires_specific) VALUES (:prix,:nom,:description,:chaise,:table,:tabouret,:banque,:presentoir,:color,:accessoire_specific)");
		 $sth->bindParam(':prix',$prix);
		 $sth->bindParam(':nom',$nom);
		 $sth->bindParam(':description',$description);
		 $sth->bindParam(':chaise',$this->implode_2D($chaise));
		 $sth->bindParam(':table',$this->implode_2D($table));
		 $sth->bindParam(':tabouret',$this->implode_2D($tabouret));
		 $sth->bindParam(':banque',$this->implode_2D($banque));
		 $sth->bindParam(':presentoir',$this->implode_2D($presentoir));
		 $sth->bindParam(':color',$color, PDO::PARAM_STR);
		 $sth->bindParam(':accessoire_specific',implode("#",$accessoire_specific));
		/*On execute*/
		return $sth->execute();
	}
		
	/**
	 * Fonction update lot
	 * Arguments : $chaise/$table... = array(array(nom,nombre,prix))
				$color = color#color
			    $accessoire_specific = array(id_accessoire)
	 * Retourne : true/false
	*/
	public function update_Lot($id,$prix, $nom, $description, $chaise, $table, $tabouret, $banque, $presentoir, $color, $accessoire_specific = array()) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("UPDATE logistic_lot SET prix = :prix, nom = :nom, description = :description, set_chaise = :chaise, set_table = :table, set_tabouret = :tabouret, set_banque = :banque , set_presentoir = :presentoir, color = :color, accessoires_specific= :accessoire_specific, date = CURRENT_TIMESTAMP WHERE id = :id");
		 $sth->bindParam(':prix',$prix);
		 $sth->bindParam(':nom',$nom);
		 $sth->bindParam(':description',$description);
		 $sth->bindParam(':chaise',$this->implode_2D($chaise));
		 $sth->bindParam(':table',$this->implode_2D($table));
		 $sth->bindParam(':tabouret',$this->implode_2D($tabouret));
		 $sth->bindParam(':banque',$this->implode_2D($banque));
		 $sth->bindParam(':presentoir',$this->implode_2D($presentoir));
		 $sth->bindParam(':color',$color, PDO::PARAM_STR);
		 $sth->bindParam(':accessoire_specific',implode("#",$accessoire_specific));	
		 $sth->bindParam(':id',$id);
		/*On execute*/
		return $sth->execute();
	}
	
	/**
	 * Fonction get lot
	 * Arguments : $id_lot
	 * Retourne : Array(id=>,nom=>,prix=>,table/chaise...=>array,color,accessoire_specific=>array)
	*/	
	public function get_Lot($id_lot) {
		$query = "SELECT * FROM logistic_lot WHERE id = ".$id_lot;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {
			$result = $result['0'];
			$return['prix'] = $result['prix']; 
			$return['nom'] = $result['nom'];
			$return['description'] = $result['description'];
			$model = Array('nom','nom','nombre','prix');
		    $elements = Array(
				'chaise',
				'tabouret',
				'table',
				'presentoir',
				'banque'
			);
			foreach($elements as $element) {
				$return[$element] = $this->explode_2D($result['set_'.$element],$model);
			}
			$return['color'] = explode("#",$result['color']);
			$return['accessoire_specific'] = !empty($result['accessoires_specific']) ? explode("#",$result['accessoires_specific']) : array();
			return $return;
		}
	}
	
	/**
	 * Fonction get all Lot
	 * Arguments : $start,$limit
	 * Retourne : Array(Array(prix=>,nom=>,description=>))
	*/
	public function get_all_Lots($start=0,$limit=30) {
		$query = "SELECT * FROM logistic_lot LIMIT ". $start .",".$limit;
		$results = $this->db->prepare($query);
	    $results->execute();
		$results = $results->fetchAll();
		if(empty($results)) {
			return false;
		} else {
			$i = 0;
			foreach($results as $result) {
				$return[$i]['id'] = $result['id']; 
				$return[$i]['prix'] = $result['prix']; 
				$return[$i]['nom'] = $result['nom']; 
				$return[$i]['description'] = $result['description'];				
				$model = Array('nom','nom','nombre','prix');
				$elements = Array(
					'chaise',
					'tabouret',
					'table',
					'presentoir',
					'banque'
				);
				foreach($elements as $element) {
					$return[$i][$element] = $this->explode_2D($result['set_'.$element],$model);
				}
				$return[$i]['color'] = explode("#",$result['color']);
				$return[$i]['accessoires_specific'] = !empty($result['accessoires_specific']) ? explode("#",$result['accessoires_specific']) : array();
				$i++;
			}
			return $return;
		}		
	}
	
	/**
	 * Fonction lotNameAvailable
	 * Arguments : $nom
	 * Retourne : TRUE/FALSE
	*/
	public function lotNameAvailable($nom) {
		$prep = $this->db->prepare('
			SELECT * FROM logistic_lot WHERE nom LIKE :name
		');
		$prep->bindParam(':name', $nom);
		$prep->execute();
		return $prep->rowCount() == 0;
	}
		
	/**
	 * Fonction delete lot
	 * Arguments : $id_lot
	 * Retourne : true/false
	*/	
	public function delete_Lot($id_lot) {
		$query = "DELETE FROM logistic_lot WHERE id = :id";
		$prep = $this->db->prepare($query);
		$prep->bindParam(':id', $id_lot);
		return $prep->execute();
	}
	
	/*
	 * FONCTION DE STAND
	 */
	
	/**
	 * Fonction ajouter/update stand
	 * Arguments : $prix, $taille, $nbstand
	 * Retourne : true/false
	*/
	public function add_Stand($prix, $taille, $nbstand) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("INSERT INTO logistic_stand VALUES ('',:prix,:taille,CURRENT_TIMESTAMP,:nbstand)");
		$sth->bindParam(':prix',$prix);
		$sth->bindParam(':taille',$taille);
		$sth->bindParam(':nbstand', $nbstand);
		/*On execute*/
		return $sth->execute();		
	}

	public function update_Stand($id, $prix, $taille, $nbstand) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("UPDATE logistic_stand SET prix = :prix, taille = :taille, date = CURRENT_TIMESTAMP, nbstand = :nbstand WHERE id = :id");
		$sth->bindParam(':prix',$prix);
		$sth->bindParam(':taille',$taille);
		$sth->bindParam(':id',$id);
		$sth->bindParam(':nbstand',$nbstand);

		/*On execute*/
		return $sth->execute();		
	}
	
	/**
	 * Fonction delete stand
	 * Arguments : $id_stand
	 * Retourne : true/false
	*/	
	public function delete_Stand($id_stand) {
		$query = "DELETE FROM logistic_stand WHERE id = :id";
		$prep = $this->db->prepare($query);
		$prep->bindParam(':id', $id_stand);
		return $prep->execute();	
	}
	
	
	/**
	 * FONCTION OPTIONS
	 */
	 
	/**
	 * Fonction ajouter option
	 * Arguments : $nom, $prix,(int)$categorie(0=>technique, 1=>comm, ???),$countable=0 ou 1 $description
	 * Retourne : true/false
	*/	
	public function add_Option($prix,$nom,$categorie,$countable,$description) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("INSERT INTO logistic_option VALUES ('',:prix,:nom,:categorie,:countable,:description,CURRENT_TIMESTAMP)");
		$sth->bindParam(':prix',$prix);
		$sth->bindParam(':nom',$nom);
		$sth->bindParam(':categorie',$categorie);
		$sth->bindParam(':countable',$countable);
		$sth->bindParam(':description',$description);
		/*On execute*/
		return $sth->execute();			
	}
		
	/**
	 * Fonction update option
	 * Arguments : $nom, $prix,(int)$categorie(0=>technique, 1=>comm, ???),$countable=0 ou 1 $description
	 * Retourne : true/false
	*/	
	public function update_Option($id,$prix,$nom,$categorie,$countable,$description) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("UPDATE logistic_option SET prix = :prix, nom = :nom, categorie = :categorie, countable = :countable, description = :description, date = CURRENT_TIMESTAMP WHERE id = :id");
		$sth->bindParam(':prix',$prix);
		$sth->bindParam(':nom',$nom);
		$sth->bindParam(':categorie',$categorie);
		$sth->bindParam(':countable',$countable);
		$sth->bindParam(':description',$description);
		$sth->bindParam(':id',$id);
		/*On execute*/
		return $sth->execute();			
	}
	
	
	
	/**
	 * Fonction get Option
	 * Arguments : $id_option
	 * Retourne : Array(prix=>,nom=>,description=>)
	*/	
	public function get_Option($id_option) {
		$query = "SELECT * FROM logistic_option WHERE id = ".$id_option;
		$result = $this->db->prepare($query);
	    	$result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {
			return $result[0];
		}		
	}
	
	/**
	 * Fonction get all Option
	 * Arguments : $start,$limit
	 * Retourne : Array(Array(prix=>,nom=>,description=>))
	*/
	public function get_all_Options($start=0,$limit=30) {
		$query = "SELECT * FROM logistic_option LIMIT ". $start .",".$limit;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {
			return $result;
		}		
	}
	
	/**
	 * Fonction optionNameAvailable
	 * Arguments : $nom
	 * Retourne : TRUE/FALSE
	*/
	public function optionNameAvailable($nom) {
		$prep = $this->db->prepare('
			SELECT * FROM logistic_option WHERE nom LIKE :name
		');
		$prep->bindParam(':name', $nom);
		$prep->execute();
		return $prep->rowCount() == 0;
	}
	
	/**
	 * Fonction delete Option
	 * Arguments : $id_option
	 * Retourne : true/false
	*/	
	public function delete_Option($id_option) {
		$query = "DELETE FROM logistic_option WHERE id = :id";
		$prep = $this->db->prepare($query);
		$prep->bindParam(':id', $id_option);
		return $prep->execute();	
	}
	
	/**
	 * Fonction get stand
	 * Arguments : $id_stand (optionnelle)
	 * Retourne : Array(prix=>,taille=>)
	*/	
	public function get_Stand($id_stand = 'all') {
		$query = "SELECT * FROM logistic_stand WHERE id = ".$id_stand;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {
			return $result[0];
		}
	}

	
	/**
	 * Fonction get all Stands
	 * Arguments : $start,$limit
	 * Retourne : Array(Array(prix=>,taille=>))
	*/
	public function get_all_Stands($start=0,$limit=30) {
		$query = "SELECT * FROM logistic_stand LIMIT ". $start .",".$limit;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result[0])) {
			return false;
		} else {
			return $result;
		}		
	}
	
	/*
	 * Fonction Accessoires
	 */
	
	/**
	 * Fonction ajouter accessoire
	 * Arguments : $nom, $prix, $description, $available_for = all pour tout, array(id_lot) sinon
	 * Retourne : true/false
	*/	
	public function add_Accessoire($prix,$nom,$description,$available_for='all') {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("INSERT INTO logistic_accessoire VALUES ('',:prix,:nom,:description,:for_all,:date)");
		$sth->bindParam(':prix',$prix);
		$sth->bindParam(':nom',$nom);
		$sth->bindParam(':date',time());
		$sth->bindParam(':description',$description);
		/*On traite differement available_for all ou pas*/
		if(!is_array($available_for)) {
			$sth->bindParam(':for_all',$t);
			$t = 1;
			return $sth->execute();
		} else {
			/*On enregistre, on recupere l'id, et on ajoute dans tout les lots*/
			$sth->bindParam(':for_all',$t);
			$t = 0;
			$sth->execute();			
			$id_accessoire = $this->db->lastInsertId();
			return $this->add_accessoire_to_lots($id_accessoire,$available_for);
		}			
	}
	
	/**
	 * Fonction update accessoire
	 * Arguments : $id, $nom, $prix, $description, $available_for = all pour tout, array(id_lot) sinon
	 * Retourne : true/false
	*/	
	public function update_Accessoire($id, $prix,$nom,$description,$available_for='all') {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("UPDATE logistic_accessoire SET prix = :prix, nom = :nom, description = :description, for_all = :for_all, date = :date WHERE id = :id");
		$sth->bindParam(':prix',$prix);
		$sth->bindParam(':nom',$nom);
		$sth->bindParam(':date',time());
		$sth->bindParam(':description',$description);
		$sth->bindParam(':id',$id);
		/*On traite differement available_for all ou pas*/
		if(!is_array($available_for)) {
			$sth->bindParam(':for_all',$t);
			$t = 1;
			/*Il faut supprimer l'id de l'accessoire dans tout les lots*/			
			if($sth->execute()) {
				$lots = $this->get_all_Lots();			
				foreach($lots as $lot) {
					$key = array_search($id,$lot['accessoires_specific']);
					if(!is_bool($key)) {
						unset($lot['accessoires_specific'][$key]);
						$sth = $this->db->prepare("UPDATE logistic_lot SET accessoires_specific = :acc, date = CURRENT_TIMESTAMP WHERE id = :id");
						$sth->bindParam(':acc',implode('#', $lot['accessoires_specific']));
						$sth->bindParam(':id',$lot['id']);
						if(!$sth->execute()) {
							return false;
						}
					}
				}
				return true;
			} else {
				return false;
			}
		} else {
			/*On enregistre, on recupere l'id, et on ajoute dans tout les lots*/
			$sth->bindParam(':for_all',$t);
			$t = 0;
			if($sth->execute())	{
				$this->delete_accessoire_from_lots($id);
				return $this->add_accessoire_to_lots($id,$available_for);
			} else {
				return false;
			}
		}			
	}	
	
	/**
	 * Fonction ajouter un accessoire a des lots
	 * Arguments : $id_accessoire : id de l'accessoire, $ids_lot = array(int) ou int
	 * Retourne : true/false
	*/   
	public function add_accessoire_to_lots($id_accessoire,$ids_lot) {
		/*on Converti $ids_lot en tableau si ce n'est pas le cas*/
		if(!is_array($ids_lot)) {
			$ids_lot['0'] = $ids_lot;
		}
		/*On recupere le tableau d'accessoire de chaque lot*/
		$query = "SELECT id,accessoires_specific FROM logistic_lot WHERE ";
		foreach($ids_lot as $id_lot) {
			$query .= "id = ". $id_lot ." OR ";
		}
		$query = substr($query,0,-3);
		/*Results = id et liste des options*/
		$sth = $this->db->prepare($query);
		if(!$sth->execute()) {	
			return false;
		} else {
			$return = true;
			$results = $sth->fetchAll();
			foreach($results as $result) {	
				$b = false;
				/*fusion des tableaux (chaines)*/
				if(!empty($result['accessoires_specific'])) {
					/*Si l'id appartient deja au tableau on ne fait rien */
					if(!in_array($id_accessoire,explode('#',$result['accessoires_specific']))) {
						$new_accessoires = $result['accessoires_specific']."#".$id_accessoire;
					} else {
						$b = true;
					}
				} else {
					$new_accessoires = $id_accessoire;
				}
				if(!$b) {
					$query = "UPDATE logistic_lot SET accessoires_specific = ".$new_accessoires." WHERE id = ".$result['id'];
					if(!$this->db->prepare($query)->execute()) {
						print_r($this->db->errorInfo());
						return false;
					}
				}
			}
			return true;
		}
	}
	
	/**
	 * Fonction supprime un accessoire specifique a des lots
	 * Arguments : $id_accessoire : id de l'accessoire, $ids_lot = array(int) ou int
	 * Retourne : true/false
	*/   
	public function delete_accessoire_from_lots($id_accessoire) {
		$lots = $this->get_all_Lots();
		foreach($lots as $lot) {
			if(in_array($id_accessoire,$lot['accessoires_specific'])) {
				unset($lot['accessoires_specific'][array_search($id_accessoire,$lot['accessoires_specific'])]);
				$query = "UPDATE logistic_lot SET accessoires_specific = '".implode('#',$lot['accessoires_specific'])."' WHERE id = ".$lot['id'];
				if(!$this->db->prepare($query)->execute()) {
					//print_r($this->db->errorInfo());
					return false;
				}
			}
		}
		return true;
	}
	
	/**
	 * Fonction delete Accessoire
	 * Arguments : $id_accessoire
	 * Retourne : true/false
	*/	
	public function delete_Accessoire($id_accessoire) {
		$query = "DELETE FROM logistic_accessoire WHERE id = :id";
		$prep = $this->db->prepare($query);
		$prep->bindParam(':id', $id_accessoire);
		if(!$this->delete_accessoire_from_lots($id_accessoire)) {
			return false;
		}
		return $prep->execute();
	}
	
	/**
	 * Fonction get Accessoire
	 * Arguments : $id_accessoire
	 * Retourne : Array(prix=>,nom=>,description=>,available_for)
	*/	
	public function get_Accessoire($id_accessoire) {
		$query = "SELECT * FROM logistic_accessoire WHERE id = ".$id_accessoire;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {
			$result = $result[0];
			if($result['for_all'] == 1) {
				$result['lot'] = 0;
			} else {
				$lots = $this->get_all_Lots();
				$lot_in = array();
				foreach($lots as $lot) {
						if(in_array($result['id'], $lot['accessoires_specific'])) {
							$lot_in = array('id'=>$lot['id'],'nom'=>$lot['id']);
						}
				}
				$result['lot'] = $lot_in;
			}
			return $result;
		}
	}
	
	/**
	 * Fonction get all Accessoire
	 * Arguments : $for_all = 0,1,2(RETOURNE TOUT LES TYPES!) $start,$limit
	 * Retourne : Array(Array(prix=>,nom=>,description=>,available_for=>))
	*/
	public function get_all_Accessoires($for_all,$start=0,$limit=30) {		
		$query = "SELECT * FROM logistic_accessoire";
		if($for_all == 0 || $for_all == 1) {
			$query .= " WHERE for_all =".  $for_all;
		}	
		$query .= " LIMIT ". $start .",".$limit;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {			
			if($for_all == 1) {
				foreach($result as $key=>$r) {
					$result[$key]['lot'] = 0;
				}
				return $result;
			} else {
				$lots = $this->get_all_Lots();				
				foreach($result as $key => $r) {
					$lot_in = array();
					foreach($lots as $lot) {
						if(in_array($r['id'], $lot['accessoires_specific'])) {
							$lot_in[] = array('id'=>intval($lot['id']), 'nom'=> $lot['nom']);
						}
					}
					$result[$key]['lot'] = $lot_in;
				}
				return $result;
			}
		}		
	}
	
	/**
	 * Fonction optionNameAvailable
	 * Arguments : $nom
	 * Retourne : TRUE/FALSE
	*/
	public function accessoireNameAvailable($nom) {
		$prep = $this->db->prepare('
			SELECT * FROM logistic_accessoire WHERE nom LIKE :name
		');
		$prep->bindParam(':name', $nom);
		$prep->execute();
		return $prep->rowCount() == 0;
	}
	
	/**
	 * Fonction ajouter réduction
	 * Arguments : $nom, $taux, $applyTo
	 * Retourne : true/false
	*/	
	public function add_Reduc($data) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("INSERT INTO logistic_cat_reduc VALUES ('',:nom,:taux,:applyTo,CURRENT_TIMESTAMP)");
		$sth->bindParam(':nom',$data['nom']);
		$sth->bindParam(':taux',$data['taux'],PDO::PARAM_INT);
		$sth->bindParam(':applyTo',$data['applyTo']);
		/*On execute*/
		return $sth->execute();			
	}
		
	/**
	 * Fonction update réduction
	 * Arguments : $id, $nom, $taux, $applyTo
	 * Retourne : true/false
	*/	
	public function update_Reduc($data) {
		/*Preparation de l'enregistrement*/
		$sth = $this->db->prepare("UPDATE logistic_cat_reduc SET nom = :nom, taux = :taux, applyTo = :applyTo, date = CURRENT_TIMESTAMP WHERE id = :id");
		$sth->bindParam(':nom',$data['nomEdit']);
		$sth->bindParam(':taux',$data['tauxEdit']);
		$sth->bindParam(':applyTo',$data['applyToEdit']);
		$sth->bindParam(':id',$data['idEdit']);
		/*On execute*/
		return $sth->execute();			
	}
	
	/**
	 * Fonction delete Réduction
	 * Arguments : $id_reduc
	 * Retourne : true/false
	*/	
	public function delete_Reduc($id_reduc) {
		$query = "DELETE FROM logistic_cat_reduc WHERE id = :id";
		$prep = $this->db->prepare($query);
		$prep->bindParam(':id', $id_reduc);
		return $prep->execute();	
	}
	
	public function get_all_Reducs($start=0,$limit=30) {
		$query = "SELECT * FROM logistic_cat_reduc LIMIT ". $start .",".$limit;
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		if(empty($result)) {
			return false;
		} else {
			return $result;
		}		
	}
	
	/*
	 * ENTREPRISE
	 */
	public function get_All_Firm() {
		//$prep = $this->db->prepare('SELECT * FROM logistic_firm LEFT JOIN entreprises ON firmid = entreprises.id ORDER BY name');
		/*Parce que ca arrange la logistique...*/
		$prep = $this->db->prepare('SELECT * FROM logistic_firm LEFT JOIN entreprises ON firmid = entreprises.id ORDER BY logistic_firm.id');
		$prep->execute();
		$results = $prep->fetchAll();
		/*Maintenant, on rends le tableau 'lisible'*/
		foreach($results as $key=>$result) {
			/*TRAITEMENT DES OBJETS SUPPLEMENTAIRES*/
		    $elements = Array(
				'chaise',
				'tabouret',
				'table',
				'presentoir',
				'banque'
			);
			foreach($elements as $element) {
				if(!empty($results[$key]['more_'.$element])) {
					$results[$key]['more_'.$element] = $this->explode_2D($results[$key]['more_'.$element]);
					/*On ajoute le nom de l'objet*/
					$prep = $this->db->prepare('
						SELECT set_'. $element .' FROM logistic_lot WHERE id ='. $results[$key]['type_lot']
					);
					$prep->execute();	
					$type = $prep->fetchAll();
					$type = $this->explode_2D($type[0]['set_'.$element]);
					foreach($results[$key]['more_'.$element] as $keym=>$more_element) {
						$results[$key]['more_'.$element][$keym]['id'] = $more_element[0];
						$results[$key]['more_'.$element][$keym]['nombre'] = $more_element[1];
						$results[$key]['more_'.$element][$keym]['nom'] = $more_element[0];
					}
				}
			}
			/*FIN DE TRAITEMENT DES OBJETS
			 *TRAITEMENT DES OPTIONS*/
			$results[$key]['options'] = $this->explode_2D($results[$key]['options']);
			foreach($results[$key]['options'] as $keyo=>$option) {
				$option_nom = $this->get_Option($option[0]);
				$results[$key]['options'][$keyo]['nom'] = $option_nom['nom'];
				$results[$key]['options'][$keyo]['nombre'] = $option[1];
			}
			/*TRAITEMENT DES ACCESSOIRES*/
			$results[$key]['accessoires'] = $this->explode_2D($results[$key]['accessoires']);
			//print_r($results[$key]['accessoires']);
			foreach($results[$key]['accessoires'] as $keya=>$accessoire) {
				unset($results[$key]['accessoires'][$keya]);
				$acc = $this->get_Accessoire($accessoire[0]);
				$results[$key]['accessoires'][$keya]['nom'] = $acc['nom'];
				$results[$key]['accessoires'][$keya]['nombre'] = $accessoire[1];
			}
			/*TRAITEMENT DU LOT*/	
			$lot = $this->get_Lot($results[$key]['type_lot']);
			if($results[$key]['type_lot'] == 0) {
				$results[$key]['type_lot'] = "Son propre matériel";			
			} else {
				$results[$key]['type_lot'] = $lot['nom'];
			}
			/*TRAITEMENT DU STAND*/	
			$stand = $this->get_Stand($results[$key]['type_stand']);
			//print_r($stand);
			$results[$key]['type_stand'] = $stand['taille'];
		}
		return $results;
	}

	/***
	 *** FACTURE
	 ***/
		 
	public function get_Facture($id_firm) {
		$prep = $this->db->prepare('SELECT * FROM logistic_firm WHERE firmid = :id');
		$prep->bindParam(':id', $id_firm);
		$prep->execute();
		$firms = $prep->fetchAll();
		if(!empty($firms)) {
			$firm = $firms[0];
			$ligne['enseigne'] = $firm['enseigne'];
			/*** STAND ***/
			$prep = $this->db->prepare('SELECT * FROM logistic_stand WHERE id = :id');
			$prep->bindParam(':id', $firm['type_stand']);			
			$prep->execute();
			$lot = $prep->fetchAll();	
			$lot = $lot[0];
			$ligne['stand'][0] = Array(
				'nombre' => "1",
				'designation' => utf8_decode($lot['taille']." m²"),
				'prix_each' => $lot['prix']." €",
				'prix_total' => $lot['prix']." €"
			);
			
			$ligne['mobilier'] = array();
			
			/*** LOT ***/
			if($firm['type_lot'] <> 0) {
			$prep = $this->db->prepare('SELECT * FROM logistic_lot WHERE id = :id');
			
			$prep->bindParam(':id', $firm['type_lot']);			
			$prep->execute();
			$lot = $prep->fetchAll();	
			$lot = $lot[0];
			$ligne['mobilier'][0] = Array(
				'nombre' => "1",
				'designation' => utf8_decode("Lot ".$lot['nom']),
				'prix_each' => $lot['prix']." €",
				'prix_total' => $lot['prix']." €"
			);
			
			$elements = Array(
				'chaise',
				'tabouret',
				'table',
				'presentoir',
				'banque'
			);
			$i = 1;
			/*** ELEMENTS SUPPLEMENTAIRES ***/
			foreach($elements as $element) {		
				$model_more = Array('nom','nom','more_nombre');
				$model_set = Array('nom','nom','set_nombre','price');
				$lot['set_'.$element] = $this->explode_2D($lot['set_'.$element],$model_set);
				if(!empty($firm['more_'.$element])) {
					$mores = $this->explode_2D($firm['more_'.$element],$model_more);
					foreach($mores as $more) {
						$ligne['mobilier'][$i] = Array(
							'nombre' => $more['more_nombre'],
							'designation' => utf8_decode(ucfirst($element)." \"".$more['nom']."\""),
							'prix_each' => $lot['set_'.$element][strtolower($more['nom'])]['price'],
							'prix_total' => $lot['set_'.$element][strtolower($more['nom'])]['price'] * $more['more_nombre']
						);
						$i++;
					}
				}
			}
			}
			/*** ACCESSOIRES ***/
			$firm['accessoires'] = $this->explode_2D($firm['accessoires']);
			foreach($firm['accessoires'] as $keyo=>$accessoire) {
				$accessoire_nom = $this->get_accessoire($accessoire[0]);
				$ligne['mobilier'][$i] = Array(
					'nombre' =>  $accessoire[1],
					'designation' => utf8_decode($accessoire_nom['nom']),
					'prix_each' => $accessoire_nom['prix'],
					'prix_total' => $accessoire_nom['prix'] * $accessoire[1]
				);
				$i++;
			}
		
			/*** OPTIONS ***/
			$ligne['options'] = array();
			$i=0;
			$firm['options'] = $this->explode_2D($firm['options']);
			foreach($firm['options'] as $keyo=>$option) {
				$option_nom = $this->get_Option($option[0]);
				$ligne['options'][$i] = Array(
					'nombre' =>  $option[1],
					'designation' => utf8_decode($option_nom['nom']),
					'prix_each' => $option_nom['prix'],
					'prix_total' => $option_nom['prix'] * $option[1]
				);
				$i++;
			}
			
			/*** REDUCTIONS ***/
			$i = Array(
				'tout' => 0,
				'stand' => 0,
				'mobilier' => 0,
				'options' => 0
			);
			
			$ligne['reductions'] = Array(
				'stand' => array(),
				'mobilier' => array(),
				'options' => array(),
				'tout' => array()
			);
			$query = "SELECT reducs_id FROM logistic_reduc WHERE firmid = :firmid";
			$sth = $this->db->prepare($query);
			$sth->bindParam(':firmid',$id_firm, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);
			if($result) {
				$result = explode("#",$result['reducs_id']);
				foreach ($result as $key => $v) {
					$query2 = "SELECT * FROM logistic_cat_reduc WHERE id = :id";
					$sth2 = $this->db->prepare($query2);
					$sth2->bindParam(':id',$v, PDO::PARAM_INT);
					$sth2->execute();
					$result2 = $sth2->fetch(PDO::FETCH_ASSOC);
					if (!empty($result2)) {
						$ligne['reductions'][$result2['applyTo']][$i[$result2['applyTo']]] = Array(
							'nom' =>  utf8_decode("Remise ".$result2['nom']),
							'taux' => $result2['taux']
						);
						$i[$result2['applyTo']]++;
					}
					
				}
			}

            /*** PRICE ***/
            $query = "SELECT price FROM logistic_firm WHERE firmid = :firmid";
			$sth = $this->db->prepare($query);
			$sth->bindParam(':firmid',$id_firm, PDO::PARAM_INT);
			$sth->execute();
			$result = $sth->fetch(PDO::FETCH_ASSOC);

            $ligne['price'] = $result['price'];

			return $ligne;
		}
		
	}
	
	public function get_firm_data($id) {
		$prep = $this->db->prepare('
			SELECT name, adress, city, postal_code, country
			FROM entreprises
			WHERE id = :id
		');
		$prep->bindParam(':id', $id, PDO::PARAM_INT);
		$prep->execute();
		return $prep->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getSumFirm() {
		$prep = $this->db->prepare('SELECT SUM(price) AS somme FROM logistic_firm');
		$prep->execute();
		$firms = $prep->fetchAll();
		return $firms[0]['somme'];
	}		

}
?>
