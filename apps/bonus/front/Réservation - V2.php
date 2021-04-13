<?php
require('fpdf.php');

//==================================================================
// Variables 
//==================================================================

$date = "24 novembre 2016";
$lieu = "Centre ProuvÈ Nancy";
$date_butoire = "24 novembre 2016";
$mail_trez="";
$email_RespoQ = "guillaume.wafflart@gmail.com";
$email_Trez = "tresorerie@est-horizon.com";
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
	$this->Cell(0,10,'Campus Artem, Rue du Sergent Blandan - CS14234',0,0,'C');
	$this->Ln(4);
	$this->Cell(0,10,'www.est-horizon.com - forum@est-horizon.com - +33 (0)3.55.66.27.13',0,0,'C');
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
    // Sortie du texte justifi√©
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
    $this->Cell(0,6,"FORUMLAIRE D'INSCRIPTION",0,1,'C');
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
				array("Adresse du siËge social",utf8_decode($_POST["adress"])."\n".utf8_decode($_POST["pc"]).''.utf8_decode($_POST["city"])."\n".utf8_decode($_POST["country"])),
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
	// Objet
	$this->Cell(0,10,'Signature : ',0,1,'B');
    // Saut de ligne
    $this->Ln();
}

//------------------------------------------------------------------
// Bon de commande - "page 3"
//------------------------------------------------------------------

function commande($fichier){
	global $date,$lieu,$date_butoire;
	// Lecture du fichier texte
    $txt = file_get_contents($fichier);
	//Tresorerie
	$this->SetFont('Arial','B',12);
	$this->MultiCell(0,5,$txt,0,'R');
	$this->Ln(15);
	//Arial 16
    $this->SetFont('Arial','',16);
    // Titre
    $this->Cell(0,6,"BON DE COMMANDE",0,1,'C');
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
				array(utf8_decode($_POST["packName"]),$_POST["packPrix"],"1",$_POST["packPrix"])
				);
	$total = $_POST["packPrix"];
	if($_POST["pub1"]!=NULL){
		$data[]= array("Flyers publicitaires","300","1","300");
		$total+=300;
	}
	if($_POST["pub2"]!=0){
		$data[]= array("PublicitÈ en regard de la page de prÈsentation","500","1","500");
		$total+=500;
	}
	if($_POST["pub3"]!=NULL){
		if($_POST["pub4"]==1){
			$data[]= array("Pub 2e de couverture","900","1","900");
		}
		else {
			$data[]= array("Pub 3e de couverture","900","1","900");
		}
		$total+=900;
	}		
	if($_POST["pub5"]!=0){
		$data[]= array("Logo de líentreprise sur le sac distribuÈ aux visiteurs","1300","1","1300");
		$total+=1300;
	}
	if($_POST["pub6"]!=0){
		$prix_tot = $_POST["pub6"]*26;
		$data[]= array("Repas supplÈmentaires","26",$_POST["pub6"],$prix_tot);
		$total+=$prix_tot;
	}	
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
	$this->Cell(0,10,"Formulaire d'Inscription et Bon de Commande ‡ retourner datÈs et signÈs avec la mention ´ Bon pour accord ª",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualitÈ \n du signataire");
	$this->Ln(-12);
	$this->Cell(75);
	$this->MultiCell(28,6,"Cachet \n de l'entreprise");
	$this->Ln(-12);
	$this->Cell(140);
	$this->MultiCell(48,6,"Signature et cachet \n du Forum Est-Horizon"); 	
	
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
$pdf->Output("factures/".$_POST["name"].".pdf","F");
$pdf->Output();

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
$mail->Body = "Yo, <br /><br /> encore des petits billets √† ajouter √† ta tresorerie. ".utf8_decode($_POST["name"]) .utf8_encode("qui a t√©l√©charg√© sa r√©servation.<br /><br />");
$mail->AddAttachment("factures/".$_POST["name"].".pdf",$_POST["name"].".pdf");
$mail->IsHTML(true);
$mail->AddAddress($email_Trez);
$mail->Send();

#Mail Respo QualitÈ
$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->From = 'forum@est-horizon.com';
$mail->FromName = 'Forum Est-Horizon';
$mail->Subject = utf8_encode('R√©servation de '.$_POST["name"]."");
$mail->Body = "
Madame, Monsieur,
<br/><br/>
Merci pour votre r√©servation. Veuillez trouver en pi√®ce jointe de ce mail la facture relative au pack auquel vous avez souscrit pour l'entreprise : ".utf8_decode($_POST["name"]).".
Nous restons √† votre disposition pour tout renseignement compl√©mentaire.
<br/><br/>
Bien cordialement,
<br/><br/>
Le Forum Est-Horizon
<br/><br/>";
$mail->AddAttachment("factures/".$_POST["name"].".pdf",$_POST["name"].".pdf");
$mail->IsHTML(true);
$mail->AddAddress($email_RespoQ);
$mail->Send();
	
#Mail √† l'entreprise
$mail = new PHPMailer();
$mail->CharSet = 'utf-8';
$mail->From = 'forum@est-horizon.com';
$mail->FromName = 'Forum Est-Horizon';
$mail->Subject = utf8_encode('R√©servation de '.$_POST["name"]."");
$mail->Body = "
Madame, Monsieur,
<br/><br/>
Merci pour votre r√©servation. Veuillez trouver en pi√®ce jointe de ce mail la facture relative au pack auquel vous avez souscrit pour l'entreprise : ".utf8_decode($_POST["name"]).".
Nous restons √† votre disposition pour tout renseignement compl√©mentaire.
<br/><br/>
Bien cordialement,
<br/><br/>
Le Forum Est-Horizon
<br/><br/>";
$mail->AddAttachment("factures/".$_POST["name"].".pdf",$_POST["name"].".pdf");
$mail->IsHTML(true);
$mail->AddAddress($_POST["email"]);
$mail->Send();
?>