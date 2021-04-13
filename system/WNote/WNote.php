<?php defined('IN_WITY') or die('Access denied');
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author  Fofif
 * @version	$Id: WNote.php 0003 09-04-2010 Fofif $
 * @desc	Affichage de messages.
 */

include 'WException.php';

class WNote {
	// Les niveaux de note
	const SYSTEM  = 'system';
	const ERROR   = 'error';
	const INFO    = 'info';
	const SUCCESS = 'success';
	
	/**
	 * Crée une nouvelle note
	 *
	 * @static
	 * @param  string $level   Niveau de la note
	 * @param  string $code    Intitulé de la note
	 * @param  string $message Message de la note
	 * @return $note
	 */
	public static function raise($level, $title, $message, $handler) {
		// Création d'une nouvelle note
		$note = array(
			'level'   => $level,
			'title'   => $title,
			'message' => $message
		);
		
		$func = 'handle_'.$handler;
		if (is_callable(array('WNote', $func))) {
			// Execution du handler
			self::$func($note);
			return $note;
		}
		else {
			// On évite de laisser l'écran vide
			die("wtNote::raise() : Unfound handler <strong>\"".$handler."\"</strong><br />Triggering note : ".$message);
		}
	}
	
	/**
	 * Dérivée de self::raise : passe un niveau précis en argument
	 */
	public static function system($title, $message, $handler = 'die') {
		$note = self::raise(self::ERROR, $title, $message, $handler);
		return $note;
	}
	
	/**
	 * Dérivée de self::raise : passe un niveau précis en argument
	 */
	public static function error($title, $message, $handler = 'session') {
		$note = self::raise(self::ERROR, $title, $message, $handler);
		return $note;
	}
	
	/**
	 * Dérivée de self::raise : passe un niveau précis en argument
	 */
	public static function info($title, $message, $handler = 'session') {
		$note = self::raise(self::INFO, $title, $message, $handler);
		return $note;
	}
	
	/**
	 * Dérivée de self::raise : passe un niveau précis en argument
	 */
	public static function success($title, $message, $handler = 'session') {
		$note = self::raise(self::SUCCESS, $title, $message, $handler);
		return $note;
	}
	
	public static function handle_ignore($note) {
		// do nothing...
	}
	
	/**
	 * Affichage de la note par un die
	 */
	public static function handle_die($note) {
		die("<br /><strong>".$note['title'].":</strong> ".$note['message']."<br />\n");
	}
	
	/**
	 * Assignation de la note parsée dans le tpl
	 * 
	 * @param  object $note  Note à traiter
	 * @return object Note
	 */
	public static function handle_assign($note) {
		$tpl = WSystem::getTemplate();
		$tpl->assign(array(
			'note_level' => $note['level'],
			'note_code'  => $note['title'],
			'note_msg'   => $note['message'],
			'css'        => $tpl->getVar('css').'<link href="/themes/system/styles/note.css" rel="stylesheet" type="text/css" media="screen" />'."\n"
		));
		
		// On récupère le code parsé
		$html = $tpl->parse('themes/system/templates/note.html');
		$tpl->clear(array('note_level', 'note_title', 'note_msg'));
		$tpl->assign('note', $tpl->getVar('note').$html);
	}
	
	/**
	 * Affichage d'une page avec la note en tant que sortie d'app
	 * 
	 * @param  object $note  Note à traiter
	 * @return object Note
	 */
	public static function handle_display($note) {
		$view = new WView();
		$view->assign(array(
			'note_level' => $note['level'],
			'note_code'  => $note['title'],
			'note_msg'   => $note['message'],
			'css'        => '/themes/system/styles/note.css'
		));
		$view->setTpl('themes/system/templates/note.html');
		$view->render();
	}
	
	// A faire : affichage d'une page personnalisée pour la note (mise hors circuit du template)
	public static function handle_custom_page() {
		
	}
	
	/**
	 * Assignation de la note parsée en session
	 * 
	 * @param  object $note  Note à traiter
	 * @return object Note
	 */
	public static function handle_session($note) {
		if (!isset($_SESSION['note_queue'])) {
			$_SESSION['note_queue'] = array($note);
		} else {
			array_push($_SESSION['note_queue'], $note);
		}
	}
	
	/**
	 * Traite la file d'attente des notes stockées en session
	 * 
	 * @param string $def_handler Handler par défaut à utiliser lors du traitement
	 */
	public static function treatNoteSession($def_handler = 'assign') {
		if (!empty($_SESSION['note_queue'])) {
			foreach ($_SESSION['note_queue'] as $note) {
				self::raise($note['level'], $note['title'], $note['message'], $def_handler);
			}
			
			// Nettoyage de la pile
			self::cleanSession();
		}
	}
	
	/**
	 * Fonction de nettoyage de la file d'attente
	 */
	public static function cleanSession() {
		unset($_SESSION['note_queue']);
	}
}


?>