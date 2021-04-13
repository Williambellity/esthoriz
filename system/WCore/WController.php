<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif
 * @version	$Id: WController.php 0002 17-04-2011 Fofif $
 */

abstract class WController {
	protected $wity;
	protected $view;
	protected $action = 'index';
	
	public function init(WMain $wity) {
		$this->wity = $wity;
		
		if (is_null($this->view)) {
			$this->view = new WView();
		}
	}
	
	abstract public function launch();
	
	protected function forward($action, $default = '') {
		if (method_exists($this, $action)) {
			//WConfig::set('route.action', $action);
			$this->action = $action;
			$this->$action();
		} else if (!empty($default)) {
			$this->forward($default);
		} else {
			throw new Exception("L'action \"".$action."\" est inconnue de l'application ".$this->getAppName().".");
			//WNote::error("Requête erronée", "Ce module ne connaît pas l'action \"".$action."\".", 'display');
		}
	}
	
	public function getView() {
		return $this->view;
	}
	
	public function setView(WView $view) {
		unset($this->view);
		$this->view = $view;
	}
	
	public function getAppName() {
		$className = str_replace('_', '-', get_class($this));
		if (strpos($className, 'Admin') === 0) {
			$appName = 'admin';
		} else {
			$appName = strtolower(str_replace(array('AdminController', 'Controller'), '', $className));
		}
		return $appName;
	}
	
	/**
	 * Retourne l'action effectivement déclenchée
	 */
	public function getTriggeredAction() {
		return $this->action;
	}
	
	/**
	 * Récupère la liste des actions prédéfinies d'une app
	 */
	public function getActionList() {
		if (!empty($this->actionList) && is_array($this->actionList)) {
			return $this->actionList;
		} else {
			return array();
		}
	}
	
	public function getAskedAction() {
		$args = WRoute::getArgs();
		if (isset($args[0])) {
			return $args[0];
		} else {
			return 'index';
		}
	}
	
	public function adminLoaded() {
		return strpos(get_class($this), 'Admin') !== false;
	}
	
	/**
	 * Recherche un fichier template en fonction du nom de l'appli et de l'action
	 * Le fichier sera cherché en priorité dans les fichiers du thème puis dans les fichiers de l'appli
	 * @return string adresse du fichier
	 */
	public function findTpl($app, $action) {
		if (file_exists($tplView = THEMES_DIR.$this->view->getTheme().DS.'templates'.DS.$app.DS.$action.'.html')) {
			return $tplView;
		} else {
			$subDir = ($this->adminLoaded() && $app != 'admin') ? 'admin' : 'front';
			if (file_exists($appView = APP_DIR.$app.DS.$subDir.DS.'templates'.DS.$action.'.html')) {
				return $appView;
			} else {
				throw new Exception("WController::findTpl() : le fichier '".$appView."' est introuvable.");
			}
		}
	}
	
	/**
	 * Déclenche l'action d'affichage du template
	 */
	protected function render($action) {
		$this->view->assign('actionForwarded', $this->action);
		
		// Définition du fichier template
		$this->view->setTpl($this->findTpl($this->getAppName(), $action));
		
		// Affichage de la vue
		$this->view->render();
	}
}

?>
