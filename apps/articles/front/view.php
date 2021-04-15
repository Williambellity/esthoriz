<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class articlesView extends WView {
	private $model;
	
	public function __construct(articlesModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function articles() {
 		$this->assign('pageTitle', 'Forum Est-Horizon | Articles');
		$this->assign('css', '/apps/galerie/front/css/profil.css');		
	}
}
?>