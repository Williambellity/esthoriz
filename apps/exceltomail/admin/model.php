<?php
/**
 * Wity CMS
 * SystÃ¨me de gestion de contenu pour tous.
 *
 * @author	Fofif <Johan Dufau>
 * @version	$Id: apps/newsletter/admin/model.php 0001 06-10-2011 Fofif $
 */

class exceltomailAdminModel {

	#========================================================
	# Functions WITY
	#========================================================	
	

	
	#========================================================
	# Functions Traitement EXCEL-MAILS
	#========================================================
	
	public function excelToArray($excel_nom_fichier,$nom_feuille){ //transforme un excel en array
	//	print(substr_count($excel_nom_fichier, 'xlsx'));
		require_once('helpers/excelPHP/PHPExcel/IOFactory.php');
		if (substr_count($excel_nom_fichier, 'xlsx') == 1){
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');// /!\ cette ligne pour des fichiers xlsx
			$objPHPExcel = $objReader->load($excel_nom_fichier);
		}
		else{
			$objPHPExcel = PHPExcel_IOFactory::load($excel_nom_fichier);
		}
		 
		//Iterating through all the sheets in the excel workbook and storing the array data
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$excel_array[$worksheet->getTitle()] = $worksheet->toArray();
		}
	//	print_r($excel_array);
		return $excel_array[$nom_feuille];	
	}
	
	public function arrayFillingEmail ($array, $text, $symbole="***"){ //les balises variable $$$001$$$ par la premiere variable de $array (de 001 à 999)
		$emailText = "";
		for ($i = 0; $i < strlen($text);$i++){
			if (substr($text,$i,3) == $symbole){
//				print("========</br>Lettre rang : ".$i."</br>");
				$m = intval(substr($text,$i+4,3))-1;
//				print("nombre array : ".$m."</br>");
				if ($m <= count($array)){
					$emailText .= $array[$m];
					$i += 8;
				}
//				print("Lettre rang : ".$i."</br>");
			}
			else {
				$emailText .=$text[$i];
			}
		}
		return $emailText;
	}
	
	
}

?>