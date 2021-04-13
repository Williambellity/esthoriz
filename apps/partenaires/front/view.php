<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class partenairesView extends WView {
	private $model;
	
	public function __construct(partenairesModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function partenaires() {
 		$this->assign('pageTitle', 'Partenaires | Forum Est-Horizon');
		/* $this->assign('css', '/apps/forum/front/css/exposants.css'); */
		$this->assign('css', '/apps/partenaires/front/css/css.css');		

	}
}
?>
