<?php defined('IN_WITY') or die('Access Denied');
/*
 * Wity
 * @copyright NkDeus 2010
 * @author Charly Poly <charly[dot]poly at live[dot]fr>
 *
 * Défintion de la structure de wity qui est modulable
 */

/**
 * Séparateur de dossiers
 */
define('DS', DIRECTORY_SEPARATOR);
/**
 * Racine du CMS
 */
define('WT_PATH', dirname(__FILE__).DS);
/**
 * Emplacement du Système
 */
define('SYS_DIR', WT_PATH.'system'.DS);
/**
 * Emplacement des Helpers
 */
define('HELPERS_DIR', WT_PATH.'helpers'.DS);
/**
 * Emplacement des Logs
 */
define('LOGS_DIR', WT_PATH.'helpers'.DS);
/**
 * Emplacement des Applications
 */
define('APP_DIR', WT_PATH.'apps'.DS);
/**
 * Emplacement des fichier de langue
 */
define('LANG_DIR', WT_PATH.'languages'.DS);
/**
 * Emplacement des librairies
 */
define('LIBS_DIR', WT_PATH.'libraries'.DS);
/**
 * Emplacement des fichier de cache
 */
define('CACHE_DIR', WT_PATH.'cache'.DS);
/**
 * Emplacement des thèmes
 */
define('THEMES_DIR', WT_PATH.'themes'.DS);

?>