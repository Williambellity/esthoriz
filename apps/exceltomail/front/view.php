<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class exceltomailView extends WView {
	private $model;
	
	public function __construct(exceltomailModel $model) {
		parent::__construct();
		$this->model = $model;
	}

	 public function Exceltomail() {
		$this->assign('pageTitle', 'Forum Est-Horizon | Exceltomail');
	}

}

?>