<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/front/view.php 0000 28-04-2011 Fofif $
 */

class QumanageView extends WView {
	private $model;
	
	public function __construct(QumanageModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */ 
	
	public function qumanage() {
		$this->assign('pageTitle', 'Forum Est-Horizon | gestion des questions');
		/* $this->assign('css', '/apps/profil/front/css/profil.css'); */
		
	}
}

?>