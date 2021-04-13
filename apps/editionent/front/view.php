<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/brochure/front/view.php 0000 28-04-2011 Fofif $
 */

class EditionentView extends WView {
	private $model;
	
	public function __construct(EditionentModel $model) {
		parent::__construct();
		$this->model = $model;
	}

	 public function editionent($ent,$contact,$factu) {
		/*if (empty($data)) {
			$data = array(0);
		}*/
		
		$this->assign("css","/apps/editionent/front/css/editionent.css");
		$this->assign('pageTitle', 'Forum Est-Horizon | Inscription');
		$this->assign('ent',$ent);
		$this->assign('factu',$factu);
		$this->assign('contact',$contact);
	}

}

?>