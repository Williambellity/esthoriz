<?php
require('fpdf.php');

//==================================================================
// Variables 
//==================================================================

$date = "19 Novembre 2020";
$annee = "2020";
$lieu = "Centre Prouvé Nancy";
$date_butoire = "19 Novembre 2020";
$mail_trez="";
$email_RespoQ = "respoqualite@est-horizon.com";
$email_Trez = "tresorerie@est-horizon.com";
$email_info = "informatique@est-horizon.com";

/* /!\ Modifications à effectuer dans les fichiers textes lettre (numéro édition et dates) */


//==================================================================
// Pagination 
//==================================================================
class PDF extends FPDF {

//------------------------------------------------------------------
// Fonctions
//------------------------------------------------------------------

function Table($w, $header, $data)
{
    // Couleurs, épaisseur du trait et police grasse
    $this->SetFillColor(127,127,127);
    $this->SetTextColor(255);
    $this->SetDrawColor(255,255,255);
    $this->SetLineWidth(0);
    $this->SetFont('','B');
    // En-tête
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(242,242,242);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Données
    $fill = false;
    foreach($data as $row)
    {
        for($i=0;$i<count($header);$i++){
			$this->Cell($w[$i],6,$row[$i],'LR',0,'L',$fill);
		}
		$this->Ln();
		$fill = !$fill;
    }
    // Trait de terminaison
    $this->Cell(array_sum($w),0,'','T');
}

//------------------------------------------------------------------
// Entête
//------------------------------------------------------------------
function Header()
{
    // Logo
    $this->Image('FEH.jpg',10,10,50);
    $this->Ln(50);
}

//------------------------------------------------------------------
// Pied de page
//------------------------------------------------------------------
function Footer()
{
    // Positionnement Ã  4 cm du bas
    $this->SetY(-40);
    // Police Arial italique 8
    $this->SetFont('Arial','I',9);
    // NumÃ©ro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln(6);
	$this->SetFont('Arial','B',9);
	$this->Cell(0,10,'FORUM EST-HORIZON',0,0,'C');
	$this->SetFont('Arial','I',9);
	$this->Ln(6);
	$this->Cell(0,10,'Campus Artem, Rue du Sergent Blandan - CS14234',0,0,'C');
	$this->Ln(4);
	$this->Cell(0,10,'www.est-horizon.com - forum@est-horizon.com - +33 (0)3.72.74.48.98',0,0,'C');
	$this->Ln(4);
	$this->Cell(0,10,'Association de Loi 1901 - Siret : 38795354000011 - APE : 748J',0,0,'C');
}

//------------------------------------------------------------------
// Lettre - "page 1"
//------------------------------------------------------------------

function lettre($fichier){
	global $date;
    // Lecture du fichier texte
    $txt = file_get_contents($fichier);
    // Police
    $this->SetFont('Arial','B',11);
	// Objet
	$this->Cell(0,10,'Objet : Forum Est-Horizon du '.$date.'',0,1,'L');
	// Police
	$this->SetFont('Arial','',11);
    // Sortie du texte justifiÃ©
    $this->MultiCell(0,5,$txt);
    // Saut de ligne
    $this->Ln();
}

//------------------------------------------------------------------
// Formulaire d'inscription - "page 2"
//------------------------------------------------------------------

function inscription($fichier){
	global $date,$lieu;
	// Lecture du fichier texte
    $txt = file_get_contents($fichier);
	//Arial 16
    $this->SetFont('Arial','',16);
    // Titre
    $this->Cell(0,6,"FORMULAIRE D'INSCRIPTION",0,1,'C');
	// Saut de ligne
    $this->Ln();
	//text
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Forum Est-Horizon, '.$date.' au '.$lieu.'',0,1,'C');
	
     // tableau
     $w = array(60,130);
	 $header = array ("Description de votre entreprise","");
	 $data = array(
				array("Nom de l'entreprise",utf8_decode($_POST["name"])),
				array("Adresse du siège social",utf8_decode($_POST["adress"])),
				);
    if(utf8_decode($_POST["compadress"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress"])));
    }
    
    array_push($data,array("Adresse de facturation (*)",utf8_decode($_POST["adress2"])));
    if(utf8_decode($_POST["compadress2"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress2"])));
    }
    array_push($data,array("Responsable au forum",utf8_decode($_POST["poste"])),
				array("Numéro de téléphone",$_POST["tel"]),
				array("Email",utf8_decode($_POST["email"])));
	 $this->Table($w, $header, $data);
	
	// Texte
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*) Si l’adresse de facturation n’est pas renseignée, la facture sera envoyée par défaut au siège social.',0,1,'L');
	
    // Sortie du texte justifié
	$this->SetFont('Arial','',11);
    $this->MultiCell(0,5,$txt);
    // Police
    $this->SetFont('Arial','B',12);
    // Saut de ligne
    $this->Ln();

	//text
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,"Formulaire d'Inscription à retourner daté et signé avec la mention « Bon pour accord »",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualité \n du signataire");
	$this->Ln(-12);
	$this->Cell(75);
	$this->MultiCell(28,6,"Cachet \n de l'entreprise");
	$this->Ln(-12);
	$this->Cell(140);
	$this->MultiCell(48,6,"Signature et cachet \n du Forum Est-Horizon"); 	
}

//------------------------------------------------------------------
// Bon de commande - "page 3"
//------------------------------------------------------------------

function commande($fichier){
	global $date,$lieu,$date_butoire,$annee;
	// Lecture du fichier texte
    $txt = file_get_contents($fichier);
	//Tresorerie
	$this->SetFont('Arial','B',12);
	$this->MultiCell(0,5,$txt,0,'R');
	$this->Ln(15);
    // Titre
	$this->SetFont('Arial','',16);
    $this->Cell(0,6,"BON DE COMMANDE",0,1,'C');   
	// Saut de ligne
    $this->Ln();
	// Numéro de commande 
	$this->SetFont('Arial','',11);
	$this->Cell(0,3,'Commande n°'.$annee.' - '. $_POST["no"] ,0,1,'C');
	// Saut de ligne
    $this->Ln();
	//text
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Forum Est-Horizon, '.$date.' au '.$lieu.'',0,1,'C');
	
 	//tableau
	$w = array(47,47,47,46);
	$header = array("Désignation","Prix unitaire (€)", "Quantité", "Total (€)");
	
	/* Data construction */
	$data = array(
				array(utf8_decode($_POST["pack"]),$_POST["prix"],"1",$_POST["prix"])
				);
	$total = $_POST["prix"];
	$data[]= array("TOTAL HT","","",$total);
	$data[]= array("TOTAL TTC(*)","","",$total);	
	$this->Table($w, $header, $data);
	
	//text
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*)Le Forum Est-Horizon est de par la loi dispensé de collecter la TVA.',0,1,'L');
	$this->SetFont('Arial','B',18);
	$this->MultiCell(0,5,"NET A PAYER TTC : ".$total. " €",0,'R');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,'Le paiement doit être effectué avant le '.$date_butoire.', au plus tard.',0,1,'L');
	$this->Ln();
	$this->Cell(0,10,"Bon de Commande à retourner daté et signé avec la mention « Bon pour accord »",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualité \n du signataire");
	$this->Ln(-12);
	$this->Cell(75);
	$this->MultiCell(28,6,"Cachet \n de l'entreprise");
	$this->Ln(-12);
	$this->Cell(140);
	$this->MultiCell(48,6,"Signature et cachet \n du Forum Est-Horizon"); 	
	
}


//------------------------------------------------------------------
// Facture (pour trésorier)
//------------------------------------------------------------------


function facture($fichier){
	global $date,$lieu,$date_butoire,$annee;
	// Lecture du fichier texte
    $txt = file_get_contents($fichier);
    // Titre
	$this->SetFont('Arial','',16);
    $this->Cell(0,6,"FACTURE",0,1,'C');   
    $this->Ln();
    // Numéro de commande 
	$this->Cell(0,3,'Numéro de facture (notre référence): '.$annee.' - '. $_POST["no"].' - 2' ,0,1,'C');
    $this->Ln();
    $this->SetFont('Arial','',10);
    $BDCtext = "";
    if(intval($_POST["BDCCode"])!=0||$_POST["BDCCode"]!=""){
        $BDCtext = "No Bon De Commande : ".$_POST["BDCCode"]." | ";
    }
    $BDCtext = $BDCtext.'date d\'émission : '.date('d/m/Y G:i:s');
    $this->Cell(0,3,$BDCtext,0,1,'C');
	$this->Ln(5);
    $this->SetFont('Arial','',11);
     // tableau
     $w = array(60,130);
	 $header = array ("Information sur l'entreprise","");
	 $data = array(
				array("Nom de l'entreprise",utf8_decode($_POST["name"])),
				array("Adresse du siège social",utf8_decode($_POST["adress"])),
				);
    if(utf8_decode($_POST["compadress"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress"])));
    }
    
    array_push($data,array("Adresse de facturation (*)",utf8_decode($_POST["adress2"])));
    if(utf8_decode($_POST["compadress2"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress2"])));
    }
    array_push($data,array("Responsable au forum",utf8_decode($_POST["poste"])),
				array("Numéro de téléphone",$_POST["tel"]),
				array("Email",utf8_decode($_POST["email"])));
	 $this->Table($w, $header, $data);
	// Saut de ligne
	// Saut de ligne
    $this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,"(*)Identique à l'adresse du siège social si non spécifiée",0,1,'L');
    
 	//tableau
	$w = array(47,47,47,46);
	$header = array("Désignation","Prix unitaire (€)", "Quantité", "Total (€)");
	
	/* Data construction */
	$data = array(
				array("Repas supplémentaires","26",utf8_decode($_POST["repas"]),26*utf8_decode($_POST["repas"]))
				);
	$total = 26*$_POST["repas"];
	/*$data[]= array("Place de parking","15",$_POST["parking"],15*$_POST["parking"]);
	$total+=15*$_POST["parking"];*/
	$data[]= array("TOTAL HT","","",$total);
	$data[]= array("TOTAL TTC(*)","","",$total);	
	$this->Table($w, $header, $data);
	
	//text
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*)Le Forum Est-Horizon est de par la loi dispensé de collecter la TVA.',0,1,'L');
	$this->SetFont('Arial','B',18);
	$this->MultiCell(0,5,"NET A PAYER TTC : ".$total. " €",0,'R');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,'valeur en votre aimable règlement',0,1,'C');
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Le paiement doit être effectué avant le '.$date_butoire.', au plus tard.',0,1,'C');
	$this->Ln();

	//Tresorerie
	$this->SetFont('Arial','B',12);
	$this->MultiCell(0,5,$txt,0,'R');
	$this->Ln(15);
	
}

}

//==================================================================
// Facture automatique
//==================================================================

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(True,40);
$pdf->facture("R_tresorerie.txt");
$pdf->Output("../../tresorerie/admin/facturesperso/".$_POST["name"]."facture.pdf","F");
$pdf->Output();
