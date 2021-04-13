<?php defined('IN_WITY') or die('Access denied');
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author    NkDeuS (fofif)
 * @version   $Id: WTemplateFile.php 0001 12-12-2010 fofif $
 * @desc      Moteur de template, class fichier
 * @copyright 2010 NkDeuS.Com
 */

class WTemplateFile {
	// Chemin complet du fichier
	private $href;
	
	// Répertoire racine
	private $baseDir;
	
	// Répertoire des fichiers compilés
	private $compilationDir;
	
	// Chemin du fichier compilé
	private $compilationHref;
	
	// Date de création du fichier .tpl original
	private $creationTime;
	
	// Etat de la compilation
	private $compiled = false;
	
	// Durée de la compilation
	private $compilationTime = 0;
	
	public function __construct($href, $baseDir, $compDir) {
		// Vérification de l'existance du fichier
		if (!file_exists($href)) {
			throw new Exception("Le fichier '".$href."' est introuvable");
		}
		
		$this->href = $href;
		$this->baseDir = $baseDir;
		$this->compilationDir = $compDir;
		$this->compilationHref = $this->createCompilationHref();
		$this->creationTime = filemtime($href);
	}
	
	/**
	 * Génère le chemin complet du fichier compilé
	 */
	private function createCompilationHref() {
		return $this->compilationDir
			.str_replace(array('/', '\\'), '-', trim(str_replace($this->baseDir, '', dirname($this->href)), DS))
			.'-'.str_replace('.tpl', '.tpl.php', basename($this->href));
	}
	
	public function getCompilationHref() {
		return $this->compilationHref;
	}
	
	public function getCompilationTime() {
		return $this->compilationTime;
	}
	
	/**
	 * Vérifie si une compilation du fichier est nécessaire
	 * 
	 * @param string $file Le nom du fichier
	 * @return bool Le cache est-il valide ?
	 */
	private function checkCompilation()
	{
		if ($this->compiled) {
			return true;
		}
		
		// Le fichier n'existe pas
		if (!file_exists($this->compilationHref)) {
			return false;
		}
		
		// Le fichier compilé est trop vieux
		$cacheDate = filemtime($this->compilationHref);
		if ($cacheDate < $this->creationTime) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Compilation du fichier
	 * 
	 * @param WTemplateCompiler Un compilateur de template
	 * @return void
	 */
	public function compile($compiler) {
		// Vérification de la compilation
		if (!$this->checkCompilation()) {
			// Temps marquant le début de la compilation
			$start = microtime(true);
			
			// Récupération du contenu du .tpl
			$code = file_get_contents($this->href);
			
			// Remplacement des éléments entre accolades en utilisant une fonction de callback
			$code = preg_replace_callback('#\{(([^\{\}\n]*(\{.+\})?)+)\}#Us', array($compiler, 'compileTplCode'), $code);
			
			// Enregistrement du fichier compilé
			$this->saveFile($code);
			
			// Mise à jour des infos
			$this->compilationTime = microtime(true) - $start; // temps de génération
		}
		
		$this->compiled = true; // fichier compilé
	}
	
	/**
	 * Enregistre le fichier
	 */
	private function saveFile($data) {
		if (!is_dir($this->compilationDir)) {
			if (!mkdir($this->compilationDir)) {
				throw new Exception("Ecriture impossible dans le répertoire ".$this->compilationDir);
			}
		}
		
		// Ouverture
		$handle = fopen($this->compilationHref, 'w');
		if (!$handle) {
			throw new Exception("Impossible d'ouvrir le fichier cache : ".$this->compilationHref);
		}
		
		// Ecriture
		fwrite($handle, $data);
		fclose($handle);
		
		// Chmod
		chmod($this->compilationHref, 0777);
	}
}

?>