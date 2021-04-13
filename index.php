<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 */

define('IN_WITY', true);
define('WITY_VERSION', '1.0');

// Niveau d'affichage des erreurs = maximal
error_reporting(E_ALL);

// Les chemins
require 'paths.php';

// Inclusion des classes principales
require SYS_DIR.'WCore'.DS.'WSystem.php';
require SYS_DIR.'WCore'.DS.'WRoute.php';
require SYS_DIR.'WCore'.DS.'WConfig.php';
require SYS_DIR.'WCore'.DS.'WRequest.php';
require SYS_DIR.'WNote'.DS.'WNote.php';
require SYS_DIR.'WCore'.DS.'WMain.php';

// Execution du main script
$wity = new WMain();

?>
