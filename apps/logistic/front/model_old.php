<?php
/**
* Wity CMS
* Système de gestion de contenu pour tous.
*
* @author	Fofif
* @version	$Id: entreprise/front/model.php 0001 14-04-2011 Fofif $
*/
 
class LogisticModel {
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
	 * Set firm !
	 * type_xxx = id du xxx, color = string (lower case) de la couleur, more = array(type_element => array(array(id,nombre/on-off))), accessoire = array(id,nombre/on-off), de meme option
	 * Modifié par Julien et devrait fonctionner, l'implode2D est fait en javascript avant envoi.
	 **/
	 
	public function set_Firm($array) {
		$query = "SELECT count(*) FROM `esthorizbdd`.`logistic_firm` WHERE `firmid` = :firmid";
		$q = $this->db->prepare($query);
		$q->bindParam(':firmid', $_SESSION['firmid']);
		$q->execute();
		$result = $q->fetch();
		
		if ($result['count(*)'] == 0) {
			$query = "INSERT INTO `esthorizbdd`.`logistic_firm` (`firmid`,`type_stand`,`type_lot`,`color`,`more_chaise`,`more_table`,`more_tabouret`,`more_banque`,`more_presentoir`,`options`,`accessoires`,`price`,`date`) VALUES (:firmid,:type_stand,:type_lot,:color,:more_chaise,:more_table,:more_tabouret,:more_banque,:more_presentoir,:options,:accessoires,:price,CURRENT_TIMESTAMP)";
		} else if ($result['count(*)'] == 1) {
			$query = "UPDATE `esthorizbdd`.`logistic_firm` SET `type_stand` = :type_stand, `type_lot` = :type_lot, `color` = :color, `more_chaise` = :more_chaise, `more_table` = :more_table, `more_tabouret` = :more_tabouret, `more_banque` = :more_banque, `more_presentoir` = :more_presentoir, `options` = :options, `accessoires` = :accessoires, `price` = :price, `date` = CURRENT_TIMESTAMP WHERE `firmid` = :firmid";
		} else {
			return false;
		}
		
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firmid', $_SESSION['firmid']);
		
		$sth->bindParam(':type_stand', $array['type_stand']);
		$sth->bindParam(':type_lot', $array['type_lot']);
		$sth->bindParam(':color', $array['color']);
		$sth->bindParam(':more_chaise',$array['more_chaise']);
		$sth->bindParam(':more_table',$array['more_table']);
		$sth->bindParam(':more_tabouret',$array['more_tabouret']);
		$sth->bindParam(':more_banque',$array['more_banque']);
		$sth->bindParam(':more_presentoir',$array['more_presentoir']);
		$sth->bindParam(':options',$array['options']);
		$sth->bindParam(':accessoires',$array['accessoires']);
		$sth->bindParam(':price',$array['price']);
	
		return $sth->execute();
	}
	
	/**
	 * Fonction inverse de implode_data, selon $model = Array($key_tt,$key1,$key2...)
	*/
	private function explode_2D($string,$model = null) {
		$data = explode('#',$string);
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
	}
	
	public function get_Accessoire($id) {
		$query = "SELECT * FROM logistic_accessoire WHERE id = :id";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$result = $sth->fetchAll();
		$result[0]['image'] = "./upload/logistic/accessoire/". strtolower($result[0]['nom']) .".jpg";		
		return $result[0];
	}
	/*
	 * FONCTION DE TRAITEMENT DES LOTS
	 */

	/**
	 * Fonction get all Lot
	 * Retourne : Array(Array(prix=>,nom=>,description=>,elements=>,color=>Array(nom=>,image=>))
	*/
	public function get_all_Lots() {
		$query = "SELECT * FROM logistic_lot";
		$results = $this->db->prepare($query);
	    $results->execute();
		$results = $results->fetchAll();
		if(empty($results)) {
			return false;
		} else {
			foreach($results as $result) {
				$i = $result['id'];
				$return[$i]['id'] = $result['id']; 
				$return[$i]['prix'] = $result['prix']; 
				$return[$i]['nom'] = $result['nom']; 
				$return[$i]['description'] = $result['description'];
				foreach(explode("#",$result['color']) as $color) {
					$return[$i]['color'][strtolower($color)]['nom'] = $color;
					$return[$i]['color'][strtolower($color)]['image'] = "./upload/logistic/".strtolower($return[$i]['nom'])."/lot_".strtolower($color).".png";
				}
				/*Accessoire*/
				$accessoires = !empty($result['accessoires_specific']) ? explode("#",$result['accessoires_specific']) : array();
				$return[$i]['accessoires_specific'] = Array();
				foreach($accessoires as $accessoire) {
					$return[$i]['accessoires_specific'][$accessoire] = $this->get_Accessoire($accessoire);
				}
				/*Element*/
				$elements = Array(
					'chaise',
					'tabouret',
					'table',
					'presentoir',
					'banque'
				);
				$model = Array('nom','nom','nombre','prix');
				foreach($elements as $element) {
					$element_t = $this->explode_2D($result['set_'.$element],$model);
					foreach($element_t as $key=>$e) {
						$return[$i][$element][$key] = $e;
						foreach(explode("#",$result['color']) as $color) {
							$return[$i][$element][$key]['image'][strtolower($color)] = "./upload/logistic/".strtolower($return[$i]['nom'])."/".$element."/".$e['nom']."_".strtolower($color).".png";
						}
					}
				}
			}
			return $return;
		}		
	}

	public function get_firm_Lot_value(&$lots) {
		$query = "SELECT type_lot, more_chaise, more_table, more_tabouret, more_banque, more_presentoir, accessoires, color FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['userid']);
		$sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result[0])) {
			$result = $result[0];
			$lots['any_record'] = true;
			$id = $result['type_lot'];
			$elements = Array(
				'chaise',
				'tabouret',
				'table',
				'presentoir',
				'banque');				
			$lots[$id]['default'] = true;
			$model = Array('nom','nom','nombre');
			foreach($elements as $element) {
				$more = $this->explode_2D($result['more_'.$element],$model);
				foreach($lots[$id][$element] as $key=>$data) {
					$lots[$id][$element][$key]['more'] = isset($more[$key]) ? $more[$key]['nombre'] : 0;
					$lots[$id][$element][$key]['total'] = $lots[$id][$element][$key]['more'] + $lots[$id][$element][$key]['nombre'];
				}
			}
			$model = Array('id','id','nombre');
			$accessoires = $this->explode_2D($result['accessoires'],$model);
			foreach($accessoires as $accessoire) {
				$lots[$id]['accessoires_specific'][$accessoire['id']] = true;
			}
			$lots[$id]['color'][$result['color']]['default'] = true;
		} else {
			$lots['any_record'] = false;
		}
	
	}

	
	/**
	 * FONCTION OPTIONS
	 */

	/**
	 * Fonction get all Option
	 * Arguments : $start,$limit
	 * Retourne : Array(Array(prix=>,nom=>,description=>))
	*/
	public function get_all_Options() {
		$query = "SELECT * FROM logistic_option";
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		foreach($result as $key=>$val) {
			$result[$key]['image'] = "./upload/logistic/option/". strtolower($val['nom']) .".png";
		}
		if(empty($result)) {
			return false;
		} else {
			return $result;
		}		
	}
	public function get_firm_Option_value(&$options) {
		$query = "SELECT options FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['userid']);
		$sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result[0])) {
			$options_default = $this->explode_2D($result[0]['options'],Array('id','id','nombre'));
			foreach($options as $key=>$option) {
				if(isset($options_default[$option['id']]) && $options_default[$option['id']]['nombre'] > 0) {
					$options[$key]['default'] = $options_default[$option['id']]['nombre'];
				} else {
					$options[$key]['default'] = 'false';
				}
			}
		} else {
			foreach($options as $key=>$option) {
				$options[$key]['default'] = 'false';
			}
		}
	}	
	/**
	 * Fonction get all Stands
	 * Arguments : $start,$limit
	 * Retourne : Array(Array(prix=>,taille=>))
	*/
	public function get_all_Stands() {
		$query = "SELECT * FROM logistic_stand";
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		foreach($result as $key=>$val) {
			$result[$key]['image'] = "./upload/logistic/stand/". strtolower($val['taille']) .".jpg";
		}
		if(empty($result[0])) {
			return false;
		} else {
			return $result;
		}		
	}
	
	public function get_firm_Stand_value(&$stands) {
		$query = "SELECT type_stand FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['userid']);
		$sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result[0])) {
			foreach($stands as $key=>$stand) {
				if($stand['id'] != $result[0]['type_stand']) {
					$stands[$key]['default'] = 'false';
				} else {
					$stands[$key]['default'] = 'true';
				}
			}
		} else {
			foreach($stands as $key=>$stand) {
				$stands[$key]['default'] = 'false';
			}
		}
	}
	/*
	 * Fonction Accessoires
	 */
	/**
	 * Fonction get all 
	 * Retourne : Array(Array(prix=>,nom=>,description=>,image=>))
	*/
	public function get_all_Accessoires() {		
		$query = "SELECT * FROM logistic_accessoire WHERE for_all = 0";
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		foreach($result as $key=>$val) {
			$result[$key]['image'] = "./upload/logistic/accessoire/". strtolower($val['nom']) .".jpg";
		}
		if(empty($result)) {
			return Array();
		} else {			
			return $result;
		}		
	}
	
	public function get_firm_Accessoire_value(&$accessoires) {
		$query = "SELECT accessoires FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['userid']);
		$sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result[0])) {
			$accessoires_default = $this->explode_2D($result[0]['accessoires'],Array('id','id','nombre'));
			foreach($accessoires as $key=>$accessoire) {
				if(isset($options_default[$accessoire['id']]) && $accessoires_default[$accessoire['id']]['nombre'] > 0) {
					$accessoires[$key]['default'] = $accessoires_default[$option['id']]['nombre'];
				} else {
					$accessoires[$key]['default'] = 'false';
				}
			}
		} else {
			foreach($accessoires as $key=>$accessoire) {
				$accessoires[$key]['default'] = 'false';
			}
		}
	}		
}
?>