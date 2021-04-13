<?php
require('fpdf.php');

//==================================================================
// Variables 
//==================================================================

$date = "2 DÈcembre 2021";
$annee = "2021";
$lieu = "Centre ProuvÈ Nancy";
$date_butoire = "2 DÈcembre 2021";
$mail_trez="";
$email_RespoQ = "forum@est-horizon.com";
$email_Trez = "tresorerie@est-horizon.com";
$email_info = "informatique@est-horizon.com";

/* /!\ Modifications ‡ effectuer dans les fichiers textes lettre (numÈro Èdition et dates) */


//==================================================================
// Pagination 
//==================================================================
class PDF extends FPDF {

//------------------------------------------------------------------
// Fonctions
//------------------------------------------------------------------

function Table($w, $header, $data)
{
    // Couleurs, Èpaisseur du trait et police grasse
    $this->SetFillColor(127,127,127);
    $this->SetTextColor(255);
    $this->SetDrawColor(255,255,255);
    $this->SetLineWidth(0);
    $this->SetFont('','B');
    // En-tÍte
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(242,242,242);
    $this->SetTextColor(0);
    $this->SetFont('');
    // DonnÈes
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
// EntÍte
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
    // Positionnement √† 4 cm du bas
    $this->SetY(-40);
    // Police Arial italique 8
    $this->SetFont('Arial','I',9);
    // Num√©ro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln(6);
	$this->SetFont('Arial','B',9);
	$this->Cell(0,10,'FORUM EST-HORIZON',0,0,'C');
	$this->SetFont('Arial','I',9);
	$this->Ln(6);
	$this->Cell(0,10,'Campus ARTEM, rue du Sergent Blandan - BP 14234 - 54042 NANCY CEDEX',0,0,'C');
	$this->Ln(4);
	$this->Cell(0,10,'TÈl: +33 (0)3.55.66.27.13',0,0,'C');
	$this->Ln(3);
	$this->Cell(0,12,'www.est-horizon.com  -  forum@est-horizon.com',0,0,'C');
	$this->Ln(2);
	$this->Cell(0,15,'Association loi 1901   -   Siret : 38795354000029   -   APE : 748J',0,0,'C');
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
	$this->Cell(0,5,'Objet : Forum Est-Horizon du '.$date.'',0,1,'L');
	// Police
	$this->SetFont('Arial','',11);
    // Sortie du texte justifi√©
	$this->Cell(0,10,'…mis le '.date('d/m/Y') ,0,1,'L');
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
				array("Adresse du siËge social",utf8_decode($_POST["adress"])."\n".utf8_decode($_POST["pc"])."\n".utf8_decode($_POST["country"])),
				array("Adresse de facturation (*)",utf8_decode($_POST["adress2"])."\n".utf8_decode($_POST["pc2"]).''.utf8_decode($_POST["city2"])."\n".utf8_decode($_POST["country2"])),
				array("Responsable au forum",$_POST["civ"] . " " . utf8_decode($_POST["nomc"]) . " " . utf8_decode($_POST["prenomc"])),
				array("NumÈro de tÈlÈphone",$_POST["tel"]),
				array("Email",utf8_decode($_POST["email"])),
				);
	 $this->Table($w, $header, $data);
	
	// Texte
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*) Si líadresse de facturation níest pas renseignÈe, la facture sera envoyÈe par dÈfaut au siËge social.',0,1,'L');
	
    // Sortie du texte justifiÈ
	$this->SetFont('Arial','',11);
    $this->MultiCell(0,5,$txt);
    // Police
    $this->SetFont('Arial','B',12);
    // Saut de ligne
    $this->Ln();

	//text
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,"Formulaire d'Inscription ‡ retourner datÈ et signÈ avec la mention ´ Bon pour accord ª",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualitÈ \ndu signataire");
	$this->Ln(-12);
	$this->Cell(75);
	$this->MultiCell(28,6,"Cachet \nde l'entreprise");
	$this->Ln(-12);
	$this->Cell(140);
	$this->MultiCell(48,6,"Signature et cachet \ndu Forum Est-Horizon"); 	
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
	// NumÈro de commande 
	$this->SetFont('Arial','',11);
	$this->Cell(0,3,'Commande n∞'.$annee.' - '. $_POST["no"] .' - 2',0,1,'C');
	// Saut de ligne
    $this->Ln();
	//text
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Forum Est-Horizon, '.$date.' au '.$lieu.'',0,1,'C');
	
 	//tableau
	$w = array(47,47,47,46);
	$header = array("DÈsignation","Prix unitaire (Ä)", "QuantitÈ", "Total (Ä)");
	
	/* Data construction */
	$data = array(
				array("Repas supplÈmentaires","26",utf8_decode($_POST["repas_nbr"]),26*utf8_decode($_POST["repas_nbr"]))
				);
	$total = 26*$_POST["repas_nbr"];
	//$data[]= array("Place de parking","15",$_POST["parking"],15*$_POST["parking"]);
	//$total+=15*$_POST["parking"];
	$data[]= array("TOTAL HT","","",$total);
	$data[]= array("TOTAL TTC(*)","","",$total);	
	$this->Table($w, $header, $data);
	
	//text
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*)Le Forum Est-Horizon est de par la loi dispensÈ de collecter la TVA.',0,1,'L');
	$this->SetFont('Arial','B',18);
	$this->MultiCell(0,5,"NET A PAYER TTC : ".$total. " Ä",0,'R');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,'Le paiement doit Ítre effectuÈ avant le '.$date_butoire.', au plus tard.',0,1,'L');
	$this->Ln();
	$this->Cell(0,10,"Bon de Commande ‡ retourner datÈ et signÈ avec la mention ´ Bon pour accord ª",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualitÈ \ndu signataire");
	$this->Ln(-12);
	$this->Cell(75);
	$this->MultiCell(28,6,"Cachet \nde l'entreprise");
	$this->Ln(-12);
	$this->Cell(140);
	$this->MultiCell(48,6,"Signature et cachet \ndu Forum Est-Horizon"); 	
	
}


//------------------------------------------------------------------
// Facture (pour trÈsorier)
//------------------------------------------------------------------


function facture($fichier){
	global $date,$lieu,$date_butoire,$annee;
	// Lecture du fichier texte
    $txt = file_get_contents($fichier);
    // Titre
	$this->SetFont('Arial','',16);
    $this->Cell(0,6,"FACTURE",0,1,'C');   
    $this->Ln();
    // NumÈro de commande 
	$this->Cell(0,3,'NumÈro de facture (notre rÈfÈrence): '.$annee.' - '. $_POST["no"] .' - 2',0,1,'C');
	$this->Ln(10);
    $this->SetFont('Arial','',11);
     // tableau
     $w = array(60,130);
	 $header = array ("Information sur l'entreprise","");
	 $data = array(
				array("Nom de l'entreprise",utf8_decode($_POST["name"])),
				array("Adresse du siËge social",utf8_decode($_POST["adress"])."\n".utf8_decode($_POST["pc"]).''.utf8_decode($_POST["city"])."\n".utf8_decode($_POST["country"])),
				array("Adresse de facturation ",utf8_decode($_POST["adress2"])."\n".utf8_decode($_POST["pc2"]).''.utf8_decode($_POST["city2"])."\n".utf8_decode($_POST["country2"])),
				);
	 $this->Table($w, $header, $data);
	// Saut de ligne
    $this->Ln(10);
	// Saut de ligne
    $this->Ln();
	
 	$w = array(47,47,47,46);
	$header = array("DÈsignation","Prix unitaire (Ä)", "QuantitÈ", "Total (Ä)");
	
	/* Data construction */
	$data = array(
				array("Repas supplÈmentaires","26",utf8_decode($_POST["repas_nbr"]),26*utf8_decode($_POST["repas_nbr"]))
				);
	$total = 26*$_POST["repas_nbr"];
	$data[]= array("Place de parking","15",$_POST["parking"],15*$_POST["parking"]);
	$total+=15*$_POST["parking"];
	$data[]= array("TOTAL HT","","",$total);
	$data[]= array("TOTAL TTC(*)","","",$total);	
	$this->Table($w, $header, $data);
	
	//text
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*)Le Forum Est-Horizon est de par la loi dispensÈ de collecter la TVA.',0,1,'L');
	$this->SetFont('Arial','B',18);
	$this->MultiCell(0,5,"NET A PAYER TTC : ".$total. " Ä",0,'R');
	$this->Ln();
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,'valeur en votre aimable rËglement',0,1,'C');
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Le paiement doit Ítre effectuÈ avant le '.$date_butoire.', au plus tard.',0,1,'C');
	$this->Ln();

	//Tresorerie
	$this->SetFont('Arial','B',12);
	$this->MultiCell(0,5,$txt,0,'R');
	$this->Ln(15);
	
}

}
//==================================================================
// Corps du programme 
//==================================================================
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(True,40);
$pdf->lettre("R_lettre.txt");
$pdf->AddPage();
$pdf->inscription("R_inscription.txt");
$pdf->AddPage();
$pdf->commande("R_tresorerie.txt");
$pdf->Output("../../tresorerie/admin/factures/".$_POST["name"]." complement.pdf","F");
$pdf->Output();


//==================================================================
// Facture automatique
//==================================================================

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(True,40);
$pdf->facture("R_tresorerie.txt");
$pdf->Output("../../tresorerie/admin/factures/".$_POST["name"]." complement facture.pdf","F");
//$pdf->Output();

//==================================================================
// Envoi par mail
//==================================================================
include( "phpmailer/class.phpmailer.php");// LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';

#Mail au Trez
$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->From = 'forum@est-horizon.com';
$mail->FromName = 'Forum Est-Horizon';
$mail->Subject = utf8_encode('R√©servation de '.$_POST["name"]."");
$mail->Body = "Yo, <br /><br /> encore des petits billets √† ajouter √† ta tresorerie. (Si j'ai bien fait mon boulot) ".utf8_decode($_POST["name"]) .utf8_encode("qui a t√©l√©charg√© sa r√©servation.<br /><br />");
$mail->AddAttachment("../../tresorerie/admin/factures/".$_POST["name"]." complement.pdf",$_POST["name"]."complement.pdf");
$mail->AddAttachment("../../tresorerie/admin/factures/".$_POST["name"]." complement facture.pdf",$_POST["name"]."complement facture.pdf");
$mail->IsHTML(true);
$mail->AddAddress($email_Trez);
$mail->Send();

#Mail au pole info
$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->From = 'forum@est-horizon.com';
$mail->FromName = 'Forum Est-Horizon';
$mail->Subject = utf8_encode('R√©servation de '.$_POST["name"]."");
$mail->Body = "Yo, <br /><br /> encore des petits billets √† ajouter √† ta tresorerie. (Si j'ai bien fait mon boulot) ".utf8_decode($_POST["name"]) .utf8_encode("qui a t√©l√©charg√© sa r√©servation.<br /><br />");
$mail->AddAttachment("../../tresorerie/admin/factures/".$_POST["name"]." complement.pdf",$_POST["name"]."complement.pdf");
$mail->AddAttachment("../../tresorerie/admin/factures/".$_POST["name"]." complement facture.pdf",$_POST["name"]."complement facture.pdf");
$mail->IsHTML(true);
$mail->AddAddress($email_info);
$mail->Send();

#Mail ‡ l`entreprise
$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->From = 'forum@est-horizon.com';
$mail->FromName = 'Forum Est-Horizon';
$mail->Subject = utf8_encode('RÈservation de '.$_POST["name"]."");
$mail->Body = "<html><head><meta http-equiv='Content-Type' content='text/html; charset='UTF-8' />
Madame, Monsieur,
<br/><br/>
Merci pour votre reservation. Veuillez trouver en piece jointe de ce mail la facture relative au pack auquel vous avez souscrit pour l`entreprise : ".utf8_decode($_POST["name"]).".
Nous restons a votre disposition pour tout renseignement complementaire.
<br/><br/>
Merci de retourner ce bon de commande signe au service tresorerie du Forum Est-Horizon.
<br/><br/>
Bien cordialement,
<br/><br/>
Le Forum Est-Horizon
<br/><br/>";
$mail->AddAttachment("factures/".$_POST["name"].".pdf",$_POST["name"].".pdf");
$mail->IsHTML(true);
$mail->AddAddress($email_Trez);
$mail->Send();