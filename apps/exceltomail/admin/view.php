<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/view.php 0001 06-10-2011 Fofif $
 */

class exceltomailAdminView extends WView {
	private $model;
	
	public function __construct(exceltomailAdminModel $model) {
		parent::__construct();
		$this->model = $model;
	}
	
	/**
	 * Fonction de chargement de la page principale du formulaire de news
	 */
	private function loadMainForm() {
		// JS / CSS
		$this->assign('js', '/helpers/ckeditor/ckeditor.js');
		$this->assign('js', '/helpers/ckfinder/ckfinder.js');
		
		$this->assign('baseDir', WRoute::getDir());
	}
	
	/**
	 * Définition des valeurs de contenu du formulaire
	 */
	private function fillMainForm($model, $data) {
		foreach ($model as $item => $default) {
			$this->assign($item, isset($data[$item]) ? $data[$item] : $default);
		}
	}
	
	public function write(array $data = array()) {
		$this->assign('css', '/apps/exceltomail/admin/css/write.css');
		$this->assign('js', '/apps/exceltomail/admin/js/write.js');
		$this->loadMainForm();
		$this->fillMainForm(
			array(
				'adresse' => "forum@est-horizon.com",
				'objet' => "Objet",
				'nomfeuille' => '',
				'nom' => '',
				'message' => ''
			),
			$data	);	
	}
	
	public function previsualisation($array){
		#----------------------------------------------------
		# Variables
		#----------------------------------------------------
		$excel_array = $array[0];
		$mail_masse['adresse'] = $array[1];
		$mail_masse['nom'] = $array[2];
		$mail_masse['objet'] = $array[3];
		$mail_masse['message'] = $array[4];		
		#----------------------------------------------------
		# Traitement
		#----------------------------------------------------
		$this->assign(array(
			'nom' => $mail_masse['nom'],
			'adresse_expediteur' => $mail_masse['adresse'],	
			'objet' => $mail_masse['objet'], ));
		$mails[]=array();
		for($i = 1; $i <= count ($excel_array)-1;$i++){
			$mails[]=array(
				'numero' => $i,
				'adresse_destinataire' => $excel_array[$i][0],
				'message' => $this->model->arrayFillingEmail ($excel_array[$i], html_entity_decode($mail_masse['message'])),
				);
		}
		foreach($mails as $mail) {
            $this->tpl->assignBlockVars('mail', $mail);
        }	
	}
	
	public function retour(){
	
	}
}

?>
