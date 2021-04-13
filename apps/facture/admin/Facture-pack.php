<?php
require('fpdf.php');

//==================================================================
// Variables 
//==================================================================

$date = "2 D�cembre 2021";
$annee = "2021";
$lieu = "Centre Prouv� Nancy";
$date_butoire = "2 D�cembre 2021";
$mail_trez="";
$email_RespoQ = "respoqualite@est-horizon.com";
$email_Trez = "tresorerie@est-horizon.com";
$email_info = "informatique@est-horizon.com";

/* /!\ Modifications � effectuer dans les fichiers textes lettre (num�ro �dition et dates) */


//==================================================================
// Pagination 
//==================================================================
class PDF extends FPDF {

//------------------------------------------------------------------
// Fonctions
//------------------------------------------------------------------

function Table($w, $header, $data)
{
    // Couleurs, �paisseur du trait et police grasse
    $this->SetFillColor(127,127,127);
    $this->SetTextColor(255);
    $this->SetDrawColor(255,255,255);
    $this->SetLineWidth(0);
    $this->SetFont('','B');
    // En-t�te
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauration des couleurs et de la police
    $this->SetFillColor(242,242,242);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Donn�es
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
// Ent�te
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
    // Positionnement à 4 cm du bas
    $this->SetY(-40);
    // Police Arial italique 8
    $this->SetFont('Arial','I',9);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln(6);
	$this->SetFont('Arial','B',9);
	$this->Cell(0,10,'FORUM EST-HORIZON',0,0,'C');
	$this->SetFont('Arial','I',9);
	$this->Ln(6);
	$this->Cell(0,10,'Campus ARTEM, rue du Sergent Blandan - BP 14234 - 54042 NANCY CEDEX',0,0,'C');
	$this->Ln(4);
	$this->Cell(0,10,'T�l: +33 (0)3.55.66.27.13',0,0,'C');
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
	$this->Cell(0,10,'Objet : Forum Est-Horizon du '.$date.'',0,1,'L');
	// Police
	$this->SetFont('Arial','',11);
    // Sortie du texte justifié
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
				array("Adresse du si�ge social",utf8_decode($_POST["adress"])),
				);
    if(utf8_decode($_POST["compadress"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress"])));
    }
    
    array_push($data,array("Adresse de facturation (*)",utf8_decode($_POST["adress2"])));
    if(utf8_decode($_POST["compadress2"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress2"])));
    }
    array_push($data,array("Responsable au Forum",utf8_decode($_POST["poste"])),
				array("Num�ro de t�l�phone",$_POST["tel"]),
				array("Email",utf8_decode($_POST["email"])));
	 $this->Table($w, $header, $data);
	
	// Texte
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*) Si l�adresse de facturation n�est pas renseign�e, la facture sera envoy�e par d�faut au si�ge social.',0,1,'L');
	
    // Sortie du texte justifi�
	$this->SetFont('Arial','',11);
    $this->MultiCell(0,5,$txt);
    // Police
    $this->SetFont('Arial','B',12);
    // Saut de ligne
    $this->Ln();

	//text
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,"Formulaire d'Inscription � retourner dat� et sign� avec la mention � Bon pour accord �",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualit� \n du signataire");
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
	// Num�ro de commande 
	$this->SetFont('Arial','',11);
	$this->Cell(0,3,'Commande n�'.$annee.' - '. $_POST["no"] ,0,1,'C');
	// Saut de ligne
    $this->Ln();
	//text
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Forum Est-Horizon, '.$date.' au '.$lieu.'',0,1,'C');
	
 	//tableau
	$w = array(47,47,47,46);
	$header = array("D�signation","Prix unitaire (�)", "Quantit�", "Total (�)");
	
	/* Data construction */
	$data = array(
				array(utf8_decode($_POST["pack"]),$_POST["prix"],$_POST["packno"],intval($_POST["packno"])*intval($_POST["prix"])));
	$total = intval($_POST["packno"])*intval($_POST["prix"]);
	$data[]= array("TOTAL HT","","",$total);
	$data[]= array("TOTAL TTC(*)","","",$total);	
	$this->Table($w, $header, $data);
	
	//text
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*)Le Forum Est-Horizon est de par la loi dispens� de collecter la TVA.',0,1,'L');
	$this->SetFont('Arial','B',18);
	$this->MultiCell(0,5,"NET A PAYER TTC : ".$total. " �",0,'R');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,'Le paiement doit �tre effectu� avant le '.$date_butoire.', au plus tard.',0,1,'L');
	$this->Ln();
	$this->Cell(0,10,"Bon de Commande � retourner dat� et sign� avec la mention � Bon pour accord �",0,1,'L');
	
	//signatures
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(7);
	$this->MultiCell(28,6,"Nom et qualit� \n du signataire");
	$this->Ln(-12);
	$this->Cell(75);
	$this->MultiCell(28,6,"Cachet \n de l'entreprise");
	$this->Ln(-12);
	$this->Cell(140);
	$this->MultiCell(48,6,"Signature et cachet \n du Forum Est-Horizon"); 	
	
}


//------------------------------------------------------------------
// Facture (pour tr�sorier)
//------------------------------------------------------------------


function facture($fichier){
	global $date,$lieu,$date_butoire,$annee;
	// Lecture du fichier texte
    $txt = file_get_contents($fichier);
    // Titre
	$this->SetFont('Arial','',16);
    $this->Cell(0,6,"FACTURE",0,1,'C');   
    $this->Ln();
    // Num�ro de commande 
	$this->Cell(0,3,'Num�ro de facture (notre r�f�rence): '.$annee.' - '. $_POST["no"] ,0,1,'C');
    // generation time
    $this->Ln();
    $this->SetFont('Arial','',10);
    $BDCtext = "";
    if(intval($_POST["BDCCode"])!=0||$_POST["BDCCode"]!=""){
        $BDCtext = "No Bon De Commande : ".$_POST["BDCCode"]." | ";
    }
    $BDCtext = $BDCtext.'date d\'�mission : '.date('d/m/Y G:i:s');
    $this->Cell(0,3,$BDCtext,0,1,'C');
	$this->Ln(10);
    $this->SetFont('Arial','',9);
     // tableau
     $w = array(60,130);
	 $header = array ("Information sur l'entreprise","");
	 $data = array(
				array("Nom de l'entreprise",utf8_decode($_POST["name"])),
				array("Adresse du si�ge social",utf8_decode($_POST["adress"])),
				);
    if(utf8_decode($_POST["compadress"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress"])));
    }
    
    array_push($data,array("Adresse de facturation (*)",utf8_decode($_POST["adress2"])));
    if(utf8_decode($_POST["compadress2"])!=""){
        array_push($data,array("",utf8_decode($_POST["compadress2"])));
    }
    array_push($data,array("Responsable au forum",utf8_decode($_POST["poste"])),
				array("Num�ro de t�l�phone",$_POST["tel"]),
				array("Email",utf8_decode($_POST["email"])));
	 $this->Table($w, $header, $data);
	// Saut de ligne
    $this->Ln();
	$this->Cell(0,5,"(*)Identique � l'adresse du si�ge social si non sp�cifi�e",0,1,'L');
	// Saut de ligne
    // $this->Ln(10);
	// Saut de ligne
    // $this->Ln();
    $this->SetFont('Arial','I',9);
	
 	//tableau
	$w = array(47,47,47,46);
	$header = array("D�signation","Prix unitaire (�)", "Quantit�", "Total (�)");
	
	/* Data construction */
	$data = array(
				array(utf8_decode($_POST["pack"]),$_POST["prix"],$_POST["packno"],$_POST["prix"])
				);
	$total = intval($_POST["packno"])*intval($_POST["prix"]);
	
    if(intval($_POST["flyerno"])!=0){
        $data[] = array("Flyers publicitaires",$_POST["flyerprix"],utf8_decode($_POST["flyerno"]),$_POST["flyerno"]*$_POST["flyerprix"]);
        $total += intval($_POST["flyerprix"])*intval($_POST["flyerno"]);
    }
	
    if(intval($_POST["optionno"])!=0){
        $data[] = array($_POST["optionnom"],$_POST["optionprix"],utf8_decode($_POST["optionno"]),$_POST["optionprix"]*utf8_decode($_POST["optionno"]));
        $total += intval($_POST["optionprix"])*intval($_POST["optionno"]);
    }
	
    if(intval($_POST["repas"])!=0){
        $data[] = array("Repas suppl�mentaires",$_POST["repasprix"],utf8_decode($_POST["repas"]),$_POST["repasprix"]*utf8_decode($_POST["repas"]));
        $total += $_POST["repasprix"]*$_POST["repas"];
    }
    /*if(intval($_POST["parking"])!=0){
        $data[]= array("Place de parking","15",$_POST["parking"],15*$_POST["parking"]);
        $total+=15*$_POST["parking"];
    }*/
    if(intval($_POST["Reduction"])!=0){
        $data[]= array("Reduction ( -".$_POST["Reduction"]."% )","","",-$total*$_POST["Reduction"]/100);
        $total = $total*(1-$_POST["Reduction"]/100);
    }
	$data[]= array("TOTAL HT","","",$total);
	$data[]= array("TOTAL TTC(*)","","",$total);	
	$this->Table($w, $header, $data);
	
	//text
	$this->Ln();
	$this->SetFont('Arial','I',9);
	$this->Cell(0,10,'(*)Le Forum Est-Horizon est de par la loi dispens� de collecter la TVA.',0,1,'L');
	$this->SetFont('Arial','B',18);
	$this->MultiCell(0,5,"NET A PAYER TTC : ".$total. " �",0,'R');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(0,10,'valeur en votre aimable r�glement',0,1,'C');
	$this->SetFont('Arial','B',11);
	$this->Cell(0,10,'Le paiement doit �tre effectu� avant le '.$date_butoire.', au plus tard.',0,1,'C');
	//Tresorerie
	$this->SetFont('Arial','B',12);
	$this->MultiCell(0,5,$txt,0,'R');
	$this->Ln(10);
	
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
