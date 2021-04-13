<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/front/view.php 0000 28-04-2011 Fofif $
 */

class ProfilView extends WView {
	private $model;
	
	public function __construct(ProfilModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillForm($items, $data) {
		foreach ($items as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
	public function profil($postData = array()) {
		$this->assign('pageTitle', 'Forum Est-Horizon | Edition de vos compléments');
		/* $this->assign('css', '/apps/profil/front/css/profil.css'); */
		
		$data = $this->model->getContact();
        if(count($data)==0 or $data == "" or $data["firmid"] == 0){
		  $this->model->repairContact();
        }
        $data = $this->model->getContact();
        $this->fillForm($data, $postData);
	}
	
	 public function bonus($pack,$ent,$contact,$factu) {
		if (empty($data)) {
			$data = array(0);
		}
		
		/* $this->assign("css","/apps/bonus/front/css/bonus.css"); */
		$this->assign("js","/apps/bonus/front/js/js.css");
		$this->assign('pageTitle', 'Forum Est-Horizon | Inscription');
		$this->assign('pack',$pack);
		$this->assign('ent',$ent);
		$this->assign('factu',$factu);
		$b=empty($_SESSION["nickname"]);
		$nn=$_SESSION["nickname"];
		$this->assign('b',$b);
		$this->assign('nn',$nn);
		$this->assign('contact',$contact);
	}
}

?>