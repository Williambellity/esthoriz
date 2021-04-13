<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/entreprise/front/view.php 0000 28-04-2011 Fofif $
 */

class EntrepriseView extends WView {
	private $model;
	
	public function __construct(EntrepriseModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	public function inscription() {
		
	}
}

?>