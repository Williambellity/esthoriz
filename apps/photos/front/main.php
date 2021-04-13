<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Yoann
 * @version	$Id: apps/photos/front/main.php 0001 22-11-2013 Yoann $
 */

class PhotosController extends WController {
	public function launch() {
		$act = $this->getAskedAction();
		$this->forward($act, 'presentation');
	}
	
	public function presentation() { 
		$this->view->assign('css', '/apps/photos/front/css/photos.css'); // Import de CSS
		$this->view->assign('pageTitle', "Photos du forum 2014"); // Définition du titre de la page
		$this->render('photos'); // On cherche /apps/photos/front/templates/photos.html
	}
 
}

?>
