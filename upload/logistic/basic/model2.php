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
			$query = "INSERT INTO `esthorizbdd`.`logistic_firm` (`firmid`,`type_stand`,`type_lot`,`color`,`more_chaise`,`more_table`,`more_tabouret`,`more_banque`,`more_presentoir`,`options`,`accessoires`,`price`,`enseigne`,`commentaire`,`date`) VALUES (:firmid,:type_stand,:type_lot,:color,:more_chaise,:more_table,:more_tabouret,:more_banque,:more_presentoir,:options,:accessoires,:enseigne,:commentaire,:price,CURRENT_TIMESTAMP)";
		} else if ($result['count(*)'] == 1) {
			$query = "UPDATE `esthorizbdd`.`logistic_firm` SET `type_stand` = :type_stand, `type_lot` = :type_lot, `color` = :color, `more_chaise` = :more_chaise, `more_table` = :more_table, `more_tabouret` = :more_tabouret, `more_banque` = :more_banque, `more_presentoir` = :more_presentoir, `options` = :options, `accessoires` = :accessoires, `price` = :price, `enseigne` = :enseigne, `commentaire` = :commentaire, `date` = CURRENT_TIMESTAMP WHERE `firmid` = :firmid";
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
		$sth->bindParam(':enseigne',$array['enseigne']);
		$sth->bindParam(':commentaire',$array['commentaire']);
	
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
		if(!empty($result)) {
			$result[0]['image'] = "./upload/logistic/accessoire/". strtolower($result[0]['nom']) .".jpg";		
			return $result[0];
		} else {
			return array();
		}
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
			$return['str'] = "";
			foreach($results as $result) {
				$i = $result['id'];
				$return[$i]['id'] = $result['id']; 
				$return[$i]['prix'] = $result['prix']; 
				$return[$i]['nom'] = $result['nom']; 
				$return[$i]['description'] = $result['description'];	
/////////////////////////***********************************///////////////////////////////////////////				
				$return[$i]['icone'] = "/upload/logistic/".strtolower($result['nom'])."/icone.jpg";
				$return['str'] .= "var tab_couleur = [];\n";
				foreach(explode("#",$result['color']) as $color) {		
					$return[$i]['color'][strtolower($color)]['nom'] = $color;		
					$return['str'] .= "tab_couleur['".strtolower($color)."'] = \"/upload/logistic/".strtolower($result['nom'])."/".strtolower($color).".jpg\";\n";
				}
				$return['str'] .= "LOGISTIC.elements.lot.lots[".$result['id']."] = new LOGISTIC.elements.lot(".$result['id'].", ".$result['prix'].", \"".$return[$i]['nom']."\", \"".$return[$i]['icone']."\", tab_couleur, \"".$result['description']."\");\n";
				
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
						$return['str'] .= "var t = [];\n";
						$return[$i][$element][$key] = $e;
						foreach(explode("#",$result['color']) as $color) {
							$file = "/upload/logistic/".strtolower($result['nom'])."/".$element."/".strtolower($e['nom'])."/".strtolower($color).".png";
							if (file_exists("../www".$file)) {
								$return['str'] .= "t['".strtolower($color)."'] = \"".$file."\";\n";
							} else {
								$return['str'] .= "t['".strtolower($color)."'] = \"/upload/logistic/".strtolower($result['nom'])."/".$element."/".strtolower($e['nom'])."/default.png\";\n";
							}
							
						}
						$return['str'] .= "LOGISTIC.elements.lot.lots[".$result['id']."].addBasicAccessoire(\"".$element."\",".$e['prix'].",\"".$e['nom']."\",t,\"morer\",".$e['nombre'].");\n";
					}
				}
			}
			return $return;
		}		
	}

	public function get_firm_Lot_value(&$lots) {
		$query = "SELECT type_lot, more_chaise, more_table, more_tabouret, more_banque, more_presentoir, accessoires, color FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['firmid']);
		$sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result[0])) {
			$result = $result[0];
			//$lots['any_record'] = true;
			$id = $result['type_lot'];
			$lots['str'] .= "LOGISTIC.elements.lot.lots[".$id."].button.select(1);\n";
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
					$lots['str'] .= "LOGISTIC.elements.lot.lots[".$id."].accBasics['".$element."s']['".$data['nom']."'].button.select(".$lots[$id][$element][$key]['more'].");\n";
					$lots[$id][$element][$key]['total'] = $lots[$id][$element][$key]['more'] + $lots[$id][$element][$key]['nombre'];
				}
			}
			$model = Array('id','id','nombre');			
			if(!empty($result['accessoires'])) {
				$accessoires = $this->explode_2D($result['accessoires'],$model);
				foreach($accessoires as $accessoire) {
					$acc = $this->get_Accessoire($accessoire['id']);
					$lots[$id]['accessoires_specific'][$accessoire['id']]['nombre'] = $accessoire['nombre'];
					if($acc['for_all'] == 0) {
						$lots['str'] .= "LOGISTIC.elements.accessoire.accessoires[".$accessoire['id']."].button.select(".$accessoire['nombre'].");\n";
					} else {
						$lots['str'] .= "LOGISTIC.elements.accfixe.accfixes[".$accessoire['id']."].button.select(".$accessoire['nombre'].");\n";
					}
				}
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
		$return = " ";
		foreach($result as $r) {
			$r['image'] = "/upload/logistic/option/". strtolower($r['nom']) .".png";
			$r['type'] = ($r['countable'] == 1) ? "morer" : "toggler";
			switch($r['categorie']) {
				case 0:
					$r['categorie'] = "Technique";
					break;
				case 1:
					$r['categorie'] = "Communication";
					break;
				case 2:
					$r['categorie'] = "Autre";
					break;
			}
			$return .= "LOGISTIC.elements.option.options[".$r['id']."] = new LOGISTIC.elements.option(".$r['id'].", ".$r['prix'].", \"".$r['nom']."\", \"".$r['image']."\", \"".$r['type']."\", \"".$r['description']."\",\"".$r['categorie']."\");\n";
		}
		$result['str'] = "";
		$result['str'] .= $return;
		if(empty($result)) {
			return null;
		} else {			
			return $result;
		}		
	}
	public function get_firm_Option_value(&$options) {
		$query = "SELECT options FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['firmid']);
		$sth->execute();
		$result = $sth->fetchAll();
		$options['str'] .= "";
		if(!empty($result[0])) {
			$options_default = $this->explode_2D($result[0]['options'],Array('id','id','nombre'));
			foreach($options as $key=>$option) {
				if(isset($options_default[$option['id']]) && $options_default[$option['id']]['nombre'] > 0) {
					$options[$key]['default'] = $options_default[$option['id']]['nombre'];
					$options['str'] .= "LOGISTIC.elements.option.options[".$option['id']."].button.select(".$options_default[$option['id']]['nombre'].");\n";
				}
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
			$return = "";
			foreach($result as $key=>$val) {
				$return .= "LOGISTIC.elements.stand.stands[".$val['id']."] = new LOGISTIC.elements.stand(".$val['id'].",".$val['prix'].",".$val['taille'].");\n";
			}
			$result['str'] = $return;
			return $result;
		}		
	}
	
	public function get_firm_Stand_value(&$stands) {
		$query = "SELECT type_stand FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['firmid']);
		$sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result[0])) {
			foreach($stands as $key=>$stand) {
				if(is_array($stand) && $stand['id'] != $result[0]['type_stand']) {
					$stands[$key]['default'] = 'false';
				} else if(is_array($stand)) {
				    $stands['str'] .= "LOGISTIC.elements.stand.stands[".$stand['id']."].button.select(1);\n";
					$stands[$key]['default'] = 'true';
				}
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
		$query = "SELECT * FROM logistic_accessoire";
		$result = $this->db->prepare($query);
	    $result->execute();
		$result = $result->fetchAll();
		$return = "";
		foreach($result as $r) {
			$r['image'] = "/upload/logistic/accessoire/". strtolower($r['nom']) .".png";
			if($r['for_all'] == 1) {
				$return .=	"LOGISTIC.elements.accfixe.accfixes[".$r['id']."] = new LOGISTIC.elements.accfixe(".$r['id'].", ".$r['prix'].", \"".$r['nom']."\", \"".$r['image']."\", \"morer\", \"".$r['description']."\");\n";

			} else {
				$query = "SELECT `id` FROM logistic_lot WHERE (`accessoires_specific` REGEXP '.#?".$r['id']."#?.' OR `accessoires_specific` REGEXP '^".$r['id']."#?.' OR `accessoires_specific` REGEXP '.#?".$r['id']."$' OR `accessoires_specific` REGEXP '^".$r['id']."$')";
				$lot = $this->db->prepare($query);	
				$lot->execute();
				$lot = $lot->fetchAll();				
				$lot_str = "[";	
				$i = 0;
				foreach($lot as $lotu) {
					if($i > 0) {
						$lot_str .= ",";
					}
					$lot_str .= $lotu['id'];
					$i++;
				}
				$lot_str .= "]";
				$return .=	"LOGISTIC.elements.accessoire.accessoires[".$r['id']."] = new LOGISTIC.elements.accessoire(".$r['id'].", ".$r['prix'].", \"".$r['nom']."\", \"".$r['image']."\", \"morer\", ".$lot_str.", \"".$r['description']."\");\n";
			}
		}
		if(empty($result)) {
			$result['str'] = $return;
			return $result;
		} else {
			$result['str'] = $return;
			return $result;
		}		
	}
	
	public function get_firm_Accessoire_value(&$accessoires) {
		$query = "SELECT accessoires FROM logistic_firm WHERE firmid = :firm";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firm',$_SESSION['firmid']);
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
		}
	}
	
	public function getAdding() {
		$query = "SELECT enseigne, commentaire FROM logistic_firm WHERE firmid = :firmid";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firmid',$_SESSION['firmid']);
		$sth->execute();
		$result = $sth->fetchAll();
	}

	public function getState() {
		$query = "SELECT count(*) FROM logistic_firm WHERE firmid = :firmid";
		$sth = $this->db->prepare($query);
		$sth->bindParam(':firmid',$_SESSION['firmid']);
		$sth->execute();
		$result = $sth->fetch();
		
		if ($result['count(*)']>0) {
			$query = "SELECT price FROM logistic_firm WHERE firmid = :firmid";
			$sth = $this->db->prepare($query);
			$sth->bindParam(':firmid',$_SESSION['firmid']);
			$sth->execute();
			$result = $sth->fetch();
			return array(0, "Vous avez réservé un stand pour un montant total de : ".$result['price']."€.");
		} else {
			return array(9, "Vous n'avez pas encore réservé votre stand.");
		}
	}	
}
?>