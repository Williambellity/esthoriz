<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class FacturecovidView extends WView {
	private $model;
	
	public function __construct(facturecovidModel $model) {
		parent::__construct();
		$this->model = $model;
	}

	 public function facturecovid($pack,$ent,$contact,$factu) {
		if (empty($data)) {
			$data = array(0);
		}
		
		/* $this->assign("css","/apps/bonus/front/css/bonus.css"); */
		$this->assign("js","/apps/facturecovid/front/js/js.css");
		$this->assign('pageTitle', 'Forum Est-Horizon | Facture Forum Virtuel');
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