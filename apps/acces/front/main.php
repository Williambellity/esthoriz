<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous. * * @author Fofif * @version	$Id: apps/acces/front/main.php 0000 18-06-2011 Fofif $ */class AccesController extends WController {	public function launch() {
		$this->view->assign('css', '/apps/acces/front/css/style.css');		$this->view->assign('pageTitle', "Informations pratiques pour accéder au Forum Est-Horizon");		$this->render('acces');	}}?>