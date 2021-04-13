<?php

class exceltomailController extends WController {

#========================================================
# Functions
#========================================================

	public function excelToArray($excel_nom_fichier,$nom_feuille){ //excel : 3 colonnes : 1.prenom 2.nom 3.adresse mail 
	//	print(substr_count($excel_nom_fichier, 'xlsx'));
		require_once('helpers/excelPHP/PHPExcel/IOFactory.php');
		if (substr_count($excel_nom_fichier, 'xlsx') == 1){
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');// /!\ cette ligne pour des fichiers xlsx
			$objPHPExcel = $objReader->load($excel_nom_fichier);
		}
		else{
			$objPHPExcel = PHPExcel_IOFactory::load($excel_nom_fichier);
		}
		 
		//Itrating through all the sheets in the excel workbook and storing the array data
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$excel_array[$worksheet->getTitle()] = $worksheet->toArray();
		}
	//	print_r($excel_array);
		return $excel_array[$nom_feuille];	
	}

	public function envoiMails($excel_array){
		include( "phpmailer/class.phpmailer.php");
		$text ="

<p>Mineuse, Mineur</p>

<p></p>

<p>Aujourd'hui est un jour crucial pour Mines Nancy : l'élection des élèves administrateurs de l'Ecole.</p>

<p>Même si une seule liste se présente, celle menée par Gabriel Henry, je t'invite fortement à participer à ce scrutin.</p>

<p>L'an dernier, 93℅ des élèves avaient signé la lettre concernant le projet de GIE, ce qui avait considérablement accru le poids de la parole des élèves administrateurs au Conseil. Pour conserver cette crédibilité, viens voter aujourd'hui !</p>

<p>Si tu n'es pas là, tu peux faire comme moi et donner procuration à quelqu'un qui est sur place.</p>

<p>Le bureau de vote est ouvert de 9h à 16h en A124.

<p></p>

<p>Bien à toi,</p>
<p>---</p>
<p>Les Élèves Administrateurs	</p>	

			
				
		";	
		for($i = 1; $i <= count ($excel_array)-1;$i++){ //on commence à 1 car la première ligne de l'excel correspond aux titres 
			$nom = $excel_array[$i][0];
			$prenom = $excel_array[$i][1];
			$adresse = $excel_array[$i][2];
			$mail_text = "
			
			
			".$text ;
			
			echo '<p>======================================== MAIL N°'.$i.' ========================================</p>';
			echo '<p>'.$prenom.'</p>';
			echo '<p>'.$mail_text.'</p>';
			$mail = new PHPMailer();
			$mail->CharSet = 'utf-8';
			$mail->From = 'antoine.bichat@mines-nancy.org';
			$mail->FromName = 'Antoine BICHAT';
			$mail->Subject = utf8_encode('Viens Votez CA AUJOURD\'HUI !');
			$mail->Body = $mail_text;	
			$mail->IsHTML(true);
			$mail->AddAddress($adresse);
			$mail->Send();		
			
		}
	}

#========================================================
# programme WITI
#========================================================

	public function __construct() {
		include 'model.php';
		$this->model = new ExceltomailModel();
		
		include 'view.php';
		$this->setView(new ExceltomailView($this->model));
		
	}
	
	
	public function launch() {
		WNote::treatNoteSession();
		
		$this->view->assign('pageTitle', "Forum Est-Horizon | Exceltomail");
		
		if ($_SESSION['nickname']!="TOKKAa"){
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte administrateur pour accéder à cette zone.", 'display');
			return;
		}
		
		$this->envoiMails($this->excelToArray('upload/exceltomail/FEH_Visiteurs.xlsx','Liste des inscrits'));
//		$action = $this->getAskedAction();
//		$this->forward($action, 'exceltomail');	
		
	}
	
	public function exceltomail() {		
		WNote::treatNoteSession();
		
		
			$this->view->exceltomail();
			$this->render('exceltomail');
	}
	
}

?>
