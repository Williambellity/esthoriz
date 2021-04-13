<?php defined('WT_PATH') or die('Access denied');
/*
 * Wity
 * @copyright NkDeus 2010
 */

require SYS_DIR.'WCore'.DS.'WController.php';
require SYS_DIR.'WCore'.DS.'WView.php';

class WMain {
	private $apps;
	
	public function __construct() {
		// Chargement des configs
		$this->loadConfigs();
		
		// Route
		$this->route();
		
		// Paramétrage des sessions
		$this->setupSession();
		
		// Paramétrage du template
		$this->setupTemplate();

        // $this->log();
		
		// Execution de l'action
		$this->exec(WRoute::getApp());
	}
	
	public function exec($appName) {
		// Gestion de l'existence de l'appli
		/* if  ($appName=='logistic')  { //A supprimer dès que la réservation est dispo 
			WNote::info("La réservation des stands n'est pas disponible pour le moment", "Vous serez notifié dès que vous pourrez y accéder", 'display');
			return null;
		} else  */if ($this->isApp($appName)) {
			// Inclusion du fichier principal de l'appli
			include APP_DIR.$appName.DS.'front'.DS.'main.php';
			$class = str_replace('-', '_', ucfirst($appName)).'Controller';
			//if (class_exists($class) && get_parent_class($class) == 'WController') {
			$controller = new $class();
			$controller->init($this);
			$controller->launch();
			return $controller;
		} else {
			WNote::info("Application introuvable", "L'application ".$appName." n'existe pas.", 'display');
			return null;
		}
	}
	
	public function getAppsList() {
		if (empty($this->apps)) {
			$apps = glob(APP_DIR.'*', GLOB_ONLYDIR);
			$this->apps = array();
			foreach ($apps as $appDir) {
				if (file_exists($appDir.DS.'front'.DS.'main.php')) {
					$this->apps[] = basename($appDir);
				}
			}
		}
		return $this->apps;
	}
	
	public function isApp($app) {
		return in_array($app, $this->getAppsList());
	}
	
	private function route() {
		WRoute::init();
		WRoute::route();
	}
	
	private function log() {
		$file = fopen(WT_PATH.'log', 'a+');
		fwrite($file, "\n".@$_SESSION['userid']." - Route : ".$_SERVER['REQUEST_URI']." / ".date('d/m/Y H:i:s', time()));
		fclose($file);
	}
	
	private function loadConfigs() {
		WConfig::load('config', SYS_DIR.'config'.DS.'config.php', 'php');
	}
	
	private function setupSession() {
		// Il suffit de l'instancier
		$session = WSystem::getSession();
		if (!$session->check_flood()) {
			$_POST = array();
		}
	}
	
	private function setupTemplate() {
		$tpl = WSystem::getTemplate();
		
		// VARIABLES GENERALES
		$tpl->assign(array(
			'siteName' => WConfig::get('config.siteName'),
			'pageTitle' => WConfig::get('config.siteName')
		));
	}
}

?>
