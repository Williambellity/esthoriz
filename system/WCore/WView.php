<?php
/*
 * Wity
 * @copyright NkDeus 2010
 *
 * Classe responsable du rendu HTML final
 */

class WView {
	public $tpl;
	
	private $themeName;
	private $tplFile;
	
	private $vars = array();
	private $specialVars = array('css', 'js');
	
	public function __construct() {
		$this->tpl = WSystem::getTemplate();
		
		// Détection du thème à charger
		$this->setTheme(WConfig::get('config.theme'));
	}
	
	public function setTheme($theme) {
		if (is_dir(THEMES_DIR.$theme)) {
			$this->themeName = $theme;
			return true;
		}
		return false;
	}
	
	public function getTheme() {
		return $this->themeName;
	}
	
	public function assign($a, $b = null) {
		if (is_string($a)) {
			$this->assignOne($a, $b);
		} else if (is_array($a)) {
			foreach ($a as $key => $value) {
				$this->assignOne($key, $value);
			}
		}
	}
	
	public function assignOne($name, $value) {
		// La variable est dite "spéciale"
		if (in_array($name, $this->specialVars)) {
			if (!isset($this->vars[$name])) {
				$this->vars[$name] = array($value);
			} else if (!in_array($value, $this->vars[$name])) {
				$this->vars[$name][] = $value;
			}
		} else {
			$this->vars[$name] = $value;
		}
	}
	
	/**
	 * Retourne une variable en "stack" avec un traitement particulier
	 * @param $stackName Nom du stack
	 * @return string
	 */
	public function getStack($stackName) {
		if (empty($this->vars[$stackName])) {
			return '';
		}
		
		switch ($stackName) {
			case 'css':
				$css = $this->tpl->getVar('css');
				foreach ($this->vars['css'] as $file) {
					$css .= sprintf(
						'<link href="%s%s" rel="stylesheet" type="text/css" />'."\n", 
						(dirname($file) == '.') ? THEMES_DIR.$this->themeName.DS.'css'.DS : '',
						$file
					);
				}
				return $css;
				break;
			
			case 'js':
				$script = $this->tpl->getVar('js');
				foreach ($this->vars['js'] as $file) {
					$script .= sprintf(
						'<script type="text/javascript" src="%s%s"></script>'."\n", 
						(dirname($file) == '.') ? THEMES_DIR.$this->themeName.DS.'js'.DS : '',
						$file
					);
				}
				return $script;
				break;
			
			default:
				return $this->tpl->getVar($stackName).$this->vars[$stackName];
				break;
		}
	}
	
	public function setTpl($file) {
		if (file_exists($file)) {
			$this->tplFile = $file;
		} else {
			throw new Exception("WView::setTpl() : le fichier '".$file."' est introuvable.");
		}
	}
	
	public function getTpl() {
		return $this->tplFile;
	}
	
	public function render() {
		if (empty($this->tplFile)) {
			throw new Exception("WView::render() : Aucun fichier template n'a été spécifié.");
		}
		
		// Traitement spécial pour variable "special"
		foreach ($this->specialVars as $stack) {
			if (!empty($this->vars[$stack])) {
				$this->vars[$stack] = $this->getStack($stack);
			} else {
				unset($this->vars[$stack]);
			}
		}
		
		// Assignation des variables
		$this->tpl->assign($this->vars);
		
		// Définition de la variable {$include}
		$this->tpl->assign('include', $this->tplFile);
		
		// Affichage
		$base = WRoute::getDir();
		if ($base == '/') {
			$this->tpl->display(THEMES_DIR.$this->themeName.DS.'templates'.DS.'index.html');
		} else {
			// Si $base n'est pas égal à la racine, on modifie les liens absolus
			$html = $this->tpl->parse(THEMES_DIR.$this->themeName.DS.'templates'.DS.'index.html');
			echo str_replace(
				array('src="/', 'href="/', 'action="/'),
				array('src="'.$base, 'href="'.$base, 'action="'.$base),
				$html
			);
		}
	}
}
?>
