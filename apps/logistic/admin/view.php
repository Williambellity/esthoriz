<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif
 * @version	$Id: entreprise/admin/view.php 0001 14-04-2011 Fofif $
 */

class LogisticAdminView extends WView {
	private $model;
	
	public function __construct(LogisticAdminModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	private function php2js ($var) {
		if (is_array($var)) {
			$res = "[";
			$array = array();
			foreach ($var as $a_var) {
				$array[] = $this->php2js($a_var);
			}
			return "[" . join(",", $array) . "]";
		}
		elseif (is_bool($var)) {
			return $var ? "true" : "false";
		}
		elseif (is_int($var) || is_integer($var) || is_double($var) || is_float($var)) {
			return $var;
		}
		elseif (is_string($var)) {
			return '\'' . addslashes(stripslashes($var)) . '\'';			
		}
		// autres cas: objets, on ne les gère pas
		return FALSE;
	}
	
	public function type_stands() {
		$this->assign('js', '/apps/logistic/admin/js/type_stand.js');
		
		/*Recuperation de la liste des types de stands*/
		$stands = $this->model->get_all_Stands();
        /*Si il y a des resultats*/
        if ($stands != false) {
            $i = 0;
            $this->tpl->assign('def',true);
            foreach($stands as $stand) {
              $this->tpl->assignBlockVars('stand', $stand);
              }              
            }  else {
			WNote::error("Pas d'enregistrement", "Il n'y a aucun stand enregistré", 'assign');
		}
	}
	
	public function type_lots($data = array(),$edit = false) {
		/*TRAITEMENT DU FORMULAIRE*/
		$this->fillForm(
			array(
				'nom' => '',
				'prix' => '', 
				'color' => '',
				'description'=> ''
			),
			$data);
			
		/*Traitement des elements*/
		$elements = Array(
			'chaise' => 'Chaises',
			'tabouret' => 'Tabourets',
			'table' => 'Tables',
			'presentoir' => 'Présentoirs',
			'banque' => 'Banques'
		);		
		foreach($elements as $key => $name) {
			if(isset($data[$key.'_nom'])) {
				$data_t = array();
				$data_t['type'] = $key;
				$data_t['type_nom'] = $name;
				$i=0;
				foreach($data[$key.'_nom'] as $key2 => $data2) {
					$data_t['values'][$i]['nom'] = isset($data[$key.'_nom'][$key2]) ? $data[$key.'_nom'][$key2] : "";
					$data_t['values'][$i]['prix'] = isset($data[$key.'_prix'][$key2]) ? $data[$key.'_prix'][$key2] : "";
					$data_t['values'][$i]['number'] = isset($data[$key.'_number'][$key2]) ? $data[$key.'_number'][$key2] : "";
					$i++;
				}
				$this->tpl->assignBlockVars('elements', $data_t);
			} else {
				$this->tpl->assignBlockVars('elements', array('type' => $key, 'type_nom' => $name, 'values' => array(array('nom' => "",'number' => 1, 'prix' => ""))));			
			}
		}
		
		if($edit != false) {
			$this->tpl->assign('edit',true);
			$this->tpl->assign('idEdit',$edit);
		}
		
		/*Recuperation de la liste des types de stands*/
		$lots = $this->model->get_all_Lots();
        /*Si il y a des resultats*/
        if ($lots != false) {
            $i = 0;
            $this->tpl->assign('def',true);
            foreach($lots as $lot) {
				foreach($elements as $key=>$element) {
					$elements_value[$key] = array('nom'=>$element,'values'=>$lot[$key]);
				}
				foreach($lot['accessoires_specific'] as $key => $acc) {
					$lot['accessoires_specific'][$key] = $this->model->get_Accessoire($acc);
				}
				$lot['elements'] = $elements_value;
				/*Pour Javascript*/
				$lot['java'] = $this->php2js($lot);
				
				$this->tpl->assignBlockVars('lot', $lot);
              }
			
        } else {
			WNote::error("Pas d'enregistrement", "Il n'y a aucun lot enregistré", 'assign');
		}		
		$this->assign('js', '/apps/logistic/admin/js/type_lot.js');
		if(isset($data['accessoire_specific']) && empty($data['accessoire_specific'])) {
			$this->assign('accessoire_specific_lot', false);
		} else {
			$this->assign('accessoire_specific_lot', true);
		}
		/*Accessoire pour le select*/
		$accessoire_specific_all = $this->model->get_all_Accessoires(0);
        /*Si il y a des resultats*/
        if ($accessoire_specific_all != false) {
            $i = 0;
            $this->tpl->assign('def_all',true);
            foreach($accessoire_specific_all as $accessoire) {
				if(isset($data['accessoire_specific']) && in_array($accessoire['id'],$data['accessoire_specific'])) {
					$accessoire['select'] = true;
				} else {
					$accessoire['select'] = false;
				}
				$this->tpl->assignBlockVars('accessoire_specific', $accessoire);
            }              
        }		
	}

	public function type_options($data = array()) {
		$this->fillForm(
			array(
				'nom' => '',
				'prix' => '', 
				'description'=> ''
			),
			$data);
		$this->assign('js', '/apps/logistic/admin/js/type_option.js');
		
        /*Recuperation de la liste des types des options*/
		$options = $this->model->get_all_Options();		
		
        /*Si il y a des resultats*/
        if ($options != false) {
            $i = 0;
            $this->tpl->assign('def',true);
            foreach($options as $option) {
			  $option['categorie_id'] = $option['categorie'];
			  switch($option['categorie']) {
				case 0: $option['categorie'] = "Technique";
						break;
				case 1: $option['categorie'] = "Communication";
						break;
				case 2: $option['categorie'] = "Autre";
						break;
				case 3: $option['categorie'] = "Repas supplémentaires";
						break;
			  }
              $this->tpl->assignBlockVars('option', $option);
              }              
            }  else {
			WNote::error("Pas d'enregistrement", "Il n'y a aucune option enregistrée", 'assign');
		}
	}
	
	public function type_accessoires($data = array()) {
		$this->fillForm(
			array(
				'nom' => '',
				'prix' => '', 
				'description'=> ''
			),
			$data);
		$this->assign('for_all',isset($data['for_all']) ? $data['for_all'] : 0);
		$this->assign('add_to_lot',isset($data['add_to_lot']) ? $data['add_to_lot'] : array());
		$this->assign('js', '/apps/logistic/admin/js/type_accessoire.js');
		
		/*Recuperation de la liste des types d'accessoires*/
		$accessoires = $this->model->get_all_Accessoires(2);
		/*Si il y a des resultats*/
        if ($accessoires != false) {
            $i = 0;
            $this->tpl->assign('def',true);
            foreach($accessoires as $accessoire) {
				$accessoire['lotphp'] = $accessoire['lot'];
				$accessoire['lot'] = $this->php2js($accessoire['lot']);
				$this->tpl->assignBlockVars('accessoire', $accessoire);
              }              
        } else {
			WNote::error("Pas d'enregistrement", "Il n'y a aucun accessoire enregistrée", 'assign');
		}
				
		$lots = $this->model->get_all_Lots(0);
        /*Si il y a des resultats*/
        if ($lots != false) {
            $i = 0;
            $this->tpl->assign('def_all',true);
            foreach($lots as $lot) {
				$this->tpl->assignBlockVars('lot', $lot);
              }              
        }
		
	}
	
	public function type_reducs($data = array()) {
		$this->fillForm(
			array(
				'nom' => '',
				'taux' => '', 
				'applyTo'=> ''
			),
			$data);
		$this->assign('js', '/apps/logistic/admin/js/type_reduc.js');
		
        /*Recuperation de la liste des types des options*/
		$reducs = $this->model->get_all_Reducs();		
		
        /*Si il y a des resultats*/
        if ($reducs != false) {
            $i = 0;
            $this->tpl->assign('def',true);
            foreach($reducs as $reduc) {
              $this->tpl->assignBlockVars('reduc', $reduc);
              }              
            }  else {
			WNote::error("Pas d'enregistrement", "Il n'y a aucune réduction enregistrée", 'assign');
		}
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillForm($model, $data) {
		foreach ($model as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
	public function firm() {
		$firm = $this->model->get_All_Firm();
		$test = $this->model->get_Facture(1211);
		//echo "<pre>";
		//print_r($test);
		//echo "</pre>";
		foreach($firm as $fir) {	
			$this->tpl->assignBlockVars('firm', $fir);
		}
		$total = $this->model->getSumFirm();
		$this->assign('sum',$total);
	}
	
	public function facture($firmid) {
		$firm_data = $this->model->get_firm_data($firmid);
		
		$this->assign('echeance', 60);
		$this->assign($firm_data);
	}

}

?>
