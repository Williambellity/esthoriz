<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class gestionNewsletterView extends WView {
	private $model;
	
	public function __construct(gestionNewsletterModel $model) {
		parent::__construct();
		$this->model = $model;
	}

    public function gestionNewsletter() {
    
 		$this->assign('pageTitle', 'Forum Est-Horizon | Gestion Newsletter');

		$this->assign('css', '/apps/gestionnewsletter/front/css/css.css');		


	}
}
?>
