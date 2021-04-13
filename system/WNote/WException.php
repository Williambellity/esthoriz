<?php defined('WT_PATH') or die('Access denied');
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @auteur	NkDeuS (fofif)
 * @version	$Id: wtException.php 0000 10-04-2010 fofif. $
 *
 * Copyright 2010 NkDeuS.Com
 */

class WException extends Exception {
	// Niveau de la note
	protected $level;

	// Titre de l'erreur
	protected $code;

	// Message
	protected $message;

	// Lien
	protected $link = null;

	/**
	 * Ajout des données
	 */
	public function __construct($level, $code, $msg) {
		$this->level   = $level;
		$this->code    = $code;

		parent::__construct($msg);
	}

	/**
	 * Récupère une donnée d'erreur
	 *
	 * @param  string $property  Nom de la valeur recherchée
	 * @return mixed
	 */
	public function get($property) {
		if (property_exists('WException', $property)) {
			return $this->$property;
		}

		return null;
	}
}

?>