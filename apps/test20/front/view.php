<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class managerView extends WView {
	private $model;
	
	public function __construct(managerModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function manager() {
 		$this->assign('pageTitle', 'Forum Est-Horizon | CV');
		/* $this->assign('css', '/apps/forum/front/css/exposants.css'); */
		$this->assign('css', '/apps/test20/front/css/layout.css');		

	}
}
?>