<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/fdh/admin/view.php 0000 10-11-2012 Fofif $
 */

class FdhAdminView extends WView {
    private $model;
	
    public function __construct(FdhModel $model) {
        parent::__construct();
		$this->model = $model;
    }
	
    public function printaller(){
        $bus = $this->model->getAllBusInfo();
		$str = "";
		$lieu_pre = "";
		// bus tri
		$bus_tri = array();
		foreach ($bus as $b) {
			// Création du groupe
            if(intval($b["Depart"])==1){
                if (strcmp($b['Lieu'], $lieu_pre) != 0) {
                    if (!empty($lieu_pre)) {
                        $str .= "</optgroup>\n";
                    }
                    $str .= "<optgroup label=\"Départ ".$b['Lieu']."\">\n";
                }

                // Réupération des places restantes
                $seats_left = $this->model->placesLeft($b['idb']);
                $seats_used = $this->model->countRegistered($b['idb']);

                $str .= "<option value=\"".$b['idb']."\"";
                //$str .= ">".date_format(date_create($b['Heure']), 'H:i');
                $str .= ">".$b['Lieu'];
                if ($b['Heure'] != 0) {
                    $str .= " à ".$b['Heure'];
                }

                if ($seats_left == 0) {
                    $str .= " (Plein)";
                } else {
                    $str .= " (".$seats_left." places)";
                }

                $str .= "</option>";

                $lieu_pre = $b['Lieu'];

                $bus_tri[intval($b['idb'])] = $b;
            }
		}
		$str .= "</optgroup>\n";
		$this->assign('bus_depart_select', $str);
    }
    
    public function printretour(){
        $bus = $this->model->getAllBusInfo();
        $str = "";
		$lieu_pre = "";
		// bus tri
		$bus_tri = array();
        foreach ($bus as $b) {
			// Création du groupe
            if(intval($b["Depart"])==0){
                if (strcmp($b['Lieu'], $lieu_pre) != 0) {
                    if (!empty($lieu_pre)) {
                        $str .= "</optgroup>\n";
                    }
                    $str .= "<optgroup label=\"Retour ".$b['Lieu']."\">\n";
                }

                // Réupération des places restantes
                $seats_left = $this->model->placesLeft($b['idb']);
                $seats_used = $this->model->countRegistered($b['idb']);
                
                $str .= "<option value=\"".$b['idb']."\"";
                //$str .= ">".date_format(date_create($b['Heure']), 'H:i');
                $str .= ">".$b['Lieu'];
                if ($b['Heure'] != 0) {
                    $str .= " à ".$b['Heure'];
                }

                if ($seats_left == 0) {
                    $str .= " (Plein)";
                } else {
                    $str .= " (".$seats_used." inscrits)";
                }

                $str .= "</option>";

                $lieu_pre = $b['Lieu'];

                $bus_tri[intval($b['idb'])] = $b;
            }
		}
		$str .= "</optgroup>\n";
		$this->assign('bus_retour_select', $str);
    }
    
    public function form($data = null) {
		if (empty($data)) {
			$data = array(0);
		}
		
		$this->assign("css","/apps/fdh/admin/css/form.css");
		if(1)//count(limite2015())<485)
		{
		// Préparation du formulaire pour les bus
		$bus = $this->model->getAllBusInfo();
		$str = "";
		$lieu_pre = "";
		// bus tri
		$bus_tri = array();
		foreach ($bus as $b) {
			// Création du groupe
            if(intval($b["Depart"])==1){
                if (strcmp($b['Lieu'], $lieu_pre) != 0) {
                    if (!empty($lieu_pre)) {
                        $str .= "</optgroup>\n";
                    }
                    $str .= "<optgroup label=\"Départ ".$b['Lieu']."\">\n";
                }

                // Réupération des places restantes
                $seats_left = $this->model->placesLeft($b['idb']);

                $str .= "<option value=\"".$b['idb']."\"";
                if ($seats_left == 0) {
                    $str .= " disabled=\"disabled\"";
                }
                //$str .= ">".date_format(date_create($b['Heure']), 'H:i');
                $str .= ">".$b['Lieu'];
                if ($b['Heure'] != 0) {
                    $str .= " à ".$b['Heure'];
                }

                if ($seats_left == 0) {
                    $str .= " (Plein)";
                } else {
                    $str .= " (".$seats_left." places)";
                }

                $str .= "</option>";

                $lieu_pre = $b['Lieu'];

                $bus_tri[intval($b['idb'])] = $b;
            }
		}
		$str .= "</optgroup>\n";
		$this->assign('bus_depart_select', $str);
            
        // almost exact copy because i'm too lazy to read what's actually in there
        $str = "";
		$lieu_pre = "";
		// bus tri
		$bus_tri = array();
        foreach ($bus as $b) {
			// Création du groupe
            if(intval($b["Depart"])==0){
                if (strcmp($b['Lieu'], $lieu_pre) != 0) {
                    if (!empty($lieu_pre)) {
                        $str .= "</optgroup>\n";
                    }
                    $str .= "<optgroup label=\"Retour ".$b['Lieu']."\">\n";
                }

                // Réupération des places restantes
                $seats_left = $this->model->placesLeft($b['idb']);

                $str .= "<option value=\"".$b['idb']."\"";
                if ($seats_left == 0) {
                    $str .= " disabled=\"disabled\"";
                }
                //$str .= ">".date_format(date_create($b['Heure']), 'H:i');
                $str .= ">".$b['Lieu'];
                if ($b['Heure'] != 0) {
                    $str .= " à ".$b['Heure'];
                }

                if ($seats_left == 0) {
                    $str .= " (Plein)";
                } else {
                    $str .= " (".$seats_left." places)";
                }

                $str .= "</option>";

                $lieu_pre = $b['Lieu'];

                $bus_tri[intval($b['idb'])] = $b;
            }
		}
		$str .= "</optgroup>\n";
		$this->assign('bus_retour_select', $str);
		
		// S'il y a des gens à réinscrire
		foreach ($data as $values) {
			// Ajout des infos sur les bus
			if (!empty($values['bus'])) {
				$values += array(
					'bus_lieu' => $bus_tri[$values['bus']]['Lieu'],
					'bus_numero' => $bus_tri[$values['bus']]['Numero'],
					'bus_heure' => $bus_tri[$values['bus']]['Heure'],
                    'bus_depart' => $bus_tri[$values['bus']]['Depart']
				);
			}
			$this->tpl->assignBlockVars('a_reinscrire', $values);
		}
		}
	else{echo("Il n'y a plus de places en vente");}
	}
	
	/**
	 * Liste les inscrits
	 * 
	 * @param array $persons
	 * @param bool $display_ecole Afficher l'école qui a fait l'enregistrement ?
	 */
	public function liste($data, $display_ecole) {
		$paymods = array('Indéfini', 'Chèque', 'Liquide', 'Exonéré',"Pumpkin","Lydia");
		$bus_persons = array();
		foreach ($data as $p) {
			$p['pay_mod'] = $paymods[$p['pay_mod']];
			
			if (isset($bus_persons[$p['busaller']])) {
				$bus_persons[$p['busaller']][] = $p;
			} else {
				$bus_persons[$p['busaller']] = array($p);
			}
		}
		$this->assign('bus_persons', $bus_persons);
		$this->assign('myuserid', $_SESSION['userid']);
		
		// Récupération de l'information des bus
		foreach ($bus_persons as $idb => $persons) {
			$this->tpl->assignBlockVars('bus', $this->model->getBusInfo($idb));
		}
		
		// Affichage des écoles
		if ($display_ecole) {
			$this->assign('nom_bde', true);
		}
	}
	
	public function bus() {
		$bus = $this->model->getAllBusInfo();
		foreach ($bus as $b) {
			$date = date_create($b['Heure']);
			$b['heure'] = date_format($date, 'H');
			$b['minute'] = date_format($date, 'i');
			$b['Numero'] = intval($b['Numero']);
			$this->tpl->assignBlockVars('bus', $b);
		}
		
		if (empty($bus)) {
			$this->tpl->assignBlockVars('bus', array(0));
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
	
	public function bde_manager($currentPage,$data_edit = array()) {
		// -- AdminStyle Helper
		include HELPERS_DIR.'adminStyle'.DS.'adminStyle.php';
		$adminStyle = new AdminStyle(array('id', 'nickname', 'email', 'date', 'last_activity'), 'date', 'DESC');
		// Enregistrement des variables de classement
		$this->tpl->assign($adminStyle->getTplVars());
		
		$n = 40; // 40 BDEs par page
		$data = $this->model->getBDEList(($currentPage-1)*$n, $n);
		foreach ($data as $values) {
			// if ($values['factivity'] == '00/00/0000 00:00') {
				// $values['factivity'] = 'N/A';
			// }
			$values['access'] = explode(',', $values['access']);
			$this->tpl->assignBlockVars('users', $values);
		}
		
		// -- Génération de la numérotation
		$count = $this->model->countBDEs();
		include HELPERS_DIR.'pagination'.DS.'pagination.php';
		$page = new Pagination($count, $n, $currentPage, '/admin/fdh/bde_manager/%d');
		$this->assign('pagination', $page->getHtml());
		$this->assign('total', $count);
		
		$countRegistered = $this->model->countRegisteredFDH();
		$this->assign('inscrits', $countRegistered);
		
		if(empty($data_edit)) {
			$this->assign('error_edit','');
		} else {
			$this->assign('error_edit',"
			<script type='text/javascript'>
			edit('".$data_edit['id']."','".$data_edit['nickname']."','".$data_edit['email']."');
			</script>
			");
		}
		
		$this->assign('css','/apps/fdh/admin/css/bde_manager.css');
		$this->assign('js','/apps/fdh/admin/js/bde_manager.js');
	}
	
	public function del($id) {
		$data = $this->model->getBDEData($id);
		$this->assign('nickname', $data['nickname']);
	}
}

