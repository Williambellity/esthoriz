<?php
require('fpdf.php');
//Head
class PDF extends FPDF
{
function header(){
$this->SetFont('Arial','B',18);
$this->Cell(50,10,"FORUM EST-HORIZON");
$this->Ln(10);
$this->SetFont('Arial','',16);
$this->Cell(50,10,"Campus Artem");
$this->Ln(10);
$this->Cell(50,10,"54 042 Nancy Cedex");
$this->Image("FEH.jpg",140,10,60);
}

//Footer
function footer(){
$this->Line(0,260,210,260);

$this->SetY(260);
$this->SetFont('Arial','B',10);
$this->Cell(30);
$this->Cell(0,10,"FORUM  EST-HORIZON -  Campus Artem, rue  du  Sergent  Blandan  -  CS 14 234 ");
$this->Ln(6);
$this->Cell(65);
$this->Cell(0,10,"Tel : + 33 (0)3 55 66 27 13");
$this->Ln(6);
$this->Cell(50);
$this->SetFont('Arial','U',10);
$this->SetTextColor(0,0,255);
$this->Cell(10,10,"www.est-horizon.com" );
$this->SetFont('Arial','',10);
$this->SetTextColor(0,0,0);
$this->Cell(25);
$this->Cell(10,10," -" );
$this->SetFont('Arial','U',10);
$this->SetTextColor(0,0,255);
$this->Cell(-5);
$this->Cell(10,10,"forum@est-horizon.com");
$this->SetFont('Arial','',10);
$this->SetTextColor(0,0,0);
$this->Ln(10);
$this->Cell(30);
$this->Cell(0,10,"Association loi 1901     -     Siret: 38795354000011     -       APE : 748J");
}

}

	    
$pdf = new PDF('P','mm','A4');
$pdf->SetAutoPageBreak(False);

/*-------------------------------------------
		PAGE 1
----------------------------------------------*/

$pdf->AddPage();
$total=0;

$pdf->SetY(50);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(5);
$pdf->Cell(50,10,"Objet : Forum Est-Horizon 2016 "); 
$pdf->Ln(10);
$pdf->SetFont('Arial','',11);
$pdf->Cell(5);
$pdf->Cell(50,10,"Jeudi 24 novembre 2016"); 
$pdf->Ln(15);
$pdf->Cell(27);
$pdf->Cell(50,10,"Madame, Monsieur,"); 
$pdf->Ln(12);
$pdf->MultiCell(180,6,"               Nous avons bien not� que vous envisagiez de participer � la 33e �dition du Forum Est-Horizon qui aura lieu le Jeudi 24 novembre 2016 au Centre Prouv� de Nancy. Nous nous r�jouissons � la perspective de vous accueillir � ce salon professionnel de rencontre entre �tudiants et entreprises. ");
$pdf->Ln(7);
$pdf->Cell(27);
$pdf->Cell(180,6,"Pour finaliser votre inscription, nous vous demandons de r�gler l�int�gralit� de votre com-");
$pdf->Ln(6);
$pdf->Cell(7);
$pdf->Cell(180,6,"mande avant le 14 novembre 2016. ");
$pdf->SetFont('Arial','B',11);
$pdf->Cell(-115);
$pdf->Cell(60,6,"Votre inscription sera ent�rin�e apr�s r�ception de la totalit� ");
$pdf->Ln(6);
$pdf->Cell(7);
$pdf->Cell(50,6,"du solde.  ");
$pdf->Ln(8);
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(180,6,"                 En cas de prestations suppl�mentaires d�livr�es le jour du Forum, une facture sp�cifique vous sera adress�e apr�s l��v�nement. ");
$pdf->Ln(6);
$pdf->Cell(20);
$pdf->Cell(50,6,"Le paiement se fera :");
$pdf->Ln(7);
$pdf->Cell(7);
$pdf->Cell(50,6,"- soit par ch�que � l�ordre du Forum Est-Horizon ;");
$pdf->Ln(7);
$pdf->Cell(7);
$pdf->Cell(50,6,"- soit par virement (RIB t�l�chargeable dans la rubrique � Vos Outils �).");
$pdf->Ln(10);
$pdf->MultiCell(180,6,"               Vous trouverez ci-dessous le Formulaire d�Inscription et le Bon de Commande correspondant � votre demande. Si vous le souhaitez, vous pouvez encore modifier votre commande et  la description de l�entreprise � partir de la rubrique � Vos Outils � depuis votre compte entreprise sur notre site : www.est-horizon.com.  Nous vous prions ensuite de nous retourner le Formulaire d�Inscription et le Bon de Commande sign�s, soit par courriel � forum@est-horizon.com soit par courrier � l�adresse suivante : ");
$pdf->Cell(20);
$pdf->MultiCell(60,6," Forum Est-Horizon \n Campus ARTEM \n 54042 Nancy Cedex");
$pdf->Ln(4);
$pdf->MultiCell(180,6,"                 Dans l�attente de votre r�ponse, nous vous prions d�agr�er l�expression de nos salutations\n distingu�es.");




/*-------------------------------------------
		PAGE 2
----------------------------------------------*/

$pdf->AddPage();
$pdf->SetY(50);
$pdf->SetFont('Arial','BU',20);
$pdf->Cell(50);
$pdf->Cell(50,10,"Formulaire d�inscription"); 
$pdf->Ln(16);
$pdf->SetFont('Arial','U',16);
$pdf->Cell(5);
$pdf->Cell(50,10,"Forum Est-Horizon 2016, le 24 novembre 2016 au Centre Prouv� � Nancy"); 
$pdf->Ln(16);
$pdf->SetFont('Arial','U',12);
$pdf->Cell(5);
$pdf->Cell(50,10,"Description de votre entreprise :"); 
$pdf->Ln(10);
$pdf->SetFont('Arial','',12);
$pdf->Cell(5);
$pdf->Cell(80,8,"Nom de l'entreprise",1,1); 
$pdf->Ln(-8);
$pdf->Cell(85);
$pdf->Cell(80,8,"",1,1); 

$pdf->Cell(5);
$pdf->Cell(80,18,"Adresse du si�ge social",1,1); 
$pdf->Ln(-18);
$pdf->Cell(85);
$pdf->Cell(80,18,"",1,1); 
$pdf->Cell(5);
$pdf->MultiCell(80,5,"Adresse de facturation \n (si l�adresse de facturation n�est pas \n renseign�e, la facture sera envoy�e par \n d�faut au si�ge social) \n ",1,1); 
$pdf->Ln(-25);
$pdf->Cell(85);
$pdf->Cell(80,25,"",1,1); 

$pdf->Cell(5);
$pdf->Cell(80,8,"Responsable pour le Forum",1,1); 
$pdf->Ln(-8);
$pdf->Cell(85);
$pdf->Cell(80,8,"",1,1); 

$pdf->Cell(5);
$pdf->Cell(80,8,"Num�ro de t�l�phone",1,1); 
$pdf->Ln(-8);
$pdf->Cell(85);
$pdf->Cell(80,8,"",1,1); 

$pdf->Cell(5);
$pdf->Cell(80,8,"E-mail",1,1); 
$pdf->Ln(-8);
$pdf->Cell(85);
$pdf->Cell(80,8,"",1,1); 
$pdf->Ln(7);

$pdf->Cell(5);
$pdf->MultiCell(180,6,"                  Veuillez v�rifier l'exactitude des informations pr�c�dentes et les corriger ou les compl�ter le cas �ch�ant (en particulier l'adresse de facturation). "); 
$pdf->Ln(4);

$pdf->Cell(5);
$pdf->MultiCell(180,6,"                  La signature du pr�sent formulaire entra�ne pour le signataire repr�sentant ".$_POST["name"]." toutes les prescriptions du r�glement et du mode d�emploi du Forum Est-Horizon dont il d�clare avoir pris connaissance.");

$pdf->Ln(4);

$pdf->Cell(5);
$pdf->MultiCell(180,6,"                  En particulier, selon l�article 6 du r�glement, en cas de rupture du contrat de la part de l�exposant, celui-ci s�engage � verser le solde dudit contrat. "); 
$pdf->Ln(4);

$pdf->Cell(5);
$pdf->MultiCell(180,6,"                  L�inscription de votre entreprise au Forum Est-Horizon ne sera prise en compte que par retour du pr�sent Formulaire d'Inscription et du Bon de Commande sign�s. "); 
$pdf->Ln(6);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(7);
$pdf->Cell(180,6,"Signature : ");



$pdf->SetFont('Arial','',12);
//$pdf->SetTextColor(255,0,0);
$pdf->SetY(92);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["name"])); 
$pdf->SetY(98);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["adress"])); 
$pdf->SetY(105);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["pc"])." ".utf8_decode($_POST["city"])); 
$pdf->SetY(110);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["country"])); 
//factu

$pdf->SetY(118);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["adress2"])); 
$pdf->SetY(124);
$pdf->SetX(100);
if($_POST["pc2"]==0)
	$pdf->Cell(50,10," ");
else
	$pdf->Cell(50,10,utf8_decode($_POST["pc2"])." ".utf8_decode($_POST["city2"])); 
	
$pdf->SetY(132);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["country2"])); 


//contact
$pdf->SetY(142);
$pdf->SetX(100);
$pdf->Cell(50,10,$_POST["civ"] . " " . utf8_decode($_POST["nomc"]) . " " . utf8_decode($_POST["prenomc"])); 
$pdf->SetY(150);
$pdf->SetX(100);
$pdf->Cell(50,10,$_POST["tel"]); 
$pdf->SetY(158);
$pdf->SetX(100);
$pdf->Cell(50,10,utf8_decode($_POST["email"])); 

$pdf->SetTextColor(0);


/*-------------------------------------------
		PAGE 3
----------------------------------------------*/
$pdf->AddPage();
$pdf->SetY(55);
$pdf->SetFont('Arial','BU',20);
$pdf->Cell(55);
$pdf->Cell(60,10,"Bon de commande");

$pdf->SetFont('Arial','',12);
$pdf->Ln(12);
$pdf->Cell(5);
$pdf->Cell(50,10,"Commande n�2016 - ". $_POST["no"]);

$pdf->SetFillColor(196,172,77);
$pdf->Ln(12);
$pdf->Cell(7);
$pdf->Cell(40,10,"D�signation",1,1,'C',1);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"Prix unitaire (�)",1,1,'C',1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"Quantit�",1,1,'C',1);

$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,"Total(�)",1,1,'C',1);

//$pdf->SetTextColor(255,0,0);

$pdf->Cell(7);
$pdf->Cell(40,10,utf8_decode($_POST["packName"]),1,1);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,$_POST["packPrix"],1,1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"1",1,1);
$total+=$_POST["packPrix"];
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,$total,1,1);

//pub 1
if($_POST["pub1"]!=NULL)
{
$pdf->Cell(7);
$pdf->SetFont('Arial','',9);
$pdf->Cell(40,10,"Flyers publicitaires",1,1);
$pdf->SetFont('Arial','',12);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"300",1,1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"1",1,1);
$total+=300;
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,"300",1,1);

}

if($_POST["pub2"]!=0)
{
$pdf->Cell(7);
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(40,5,"Publicit� en regard de la page de pr�sentation ",1,1);
$pdf->SetFont('Arial','',12);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"500",1,1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"1",1,1);
$total+=500;
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,"500",1,1);

}

if($_POST["pub3"]!=NULL)
{
$pdf->Cell(7);
$pdf->SetFont('Arial','',9);
if($_POST["pub4"]==1)
	$pdf->MultiCell(40,5,"Publicit� sur la 2e page de couverture ",1,1);
else
	$pdf->MultiCell(40,5,"Publicit� sur la 3e page de couverture ",1,1);
$pdf->SetFont('Arial','',12);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"900",1,1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"1",1,1);
$total+=900;
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,"900",1,1);
}

if($_POST["pub5"]!=0)
{
$pdf->Cell(7);
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(40,5,"Logo de l�entreprise sur le sac distribu� aux visiteurs",1,1);
$pdf->SetFont('Arial','',12);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"1300",1,1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"1",1,1);
$total+=1300;
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,"1300",1,1);
}

//repas
if($_POST["pub6"]!=0)
{$pdf->SetFont('Arial','',9);
$pdf->Cell(7);
$pdf->Cell(40,10,"Repas",1,1);
$pdf->SetFont('Arial','',12);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"26",1,1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,$_POST["pub6"],1,1);
$total+=26*$_POST["pub6"];
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,26*$_POST["pub6"],1,1);
}




$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(0);
$pdf->Cell(7);
$pdf->Cell(120,10,"TOTAL HT: ",1,1);
$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,$total,1,1);
$pdf->SetTextColor(0);

$pdf->SetTextColor(0);
$pdf->Cell(7);
$pdf->Cell(120,10,"TOTAL TTC*: ",1,1);

$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,$total,1,1);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',12);

$pdf->Cell(7);
$pdf->Cell(50,10,"*Le Forum Est-Horizon est de par la loi dispens� de collecter la TVA.");
$pdf->Ln(12);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(87);
$pdf->Cell(50,10,"NET A PAYER TTC :  ");


$pdf->Cell(15);
$pdf->Cell(40,10,$total. " �");


$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(0);
$pdf->Cell(50);
$pdf->Cell(50,10,"Valeur en votre aimable r�glement");


$pdf->Ln(10);
$pdf->Cell(7);
$pdf->Cell(50,10,"Le paiement doit �tre effectu� avant le Jeudi 24 novembre 2016, au plus tard.");
$pdf->SetFont('Arial','',10);

$pdf->Ln(18);
$pdf->Cell(10);
$pdf->MultiCell(68,6,"Formulaire d'Inscription et Bon de Commande � retourner dat� et sign� avec la mention � Bon pour accord �");

$pdf->SetFont('Arial','B',12);
$pdf->Ln(-17);
$pdf->Cell(90);
$pdf->MultiCell(80,6,"                    Alice DELMAS \n     Tr�sori�re du Forum Est-Horizon                            2015-2016\n                    06 59 51 56 27 \n          tresorie@est-horizon.com\n",1,1);


$pdf->SetFont('Arial','',10);
$pdf->Ln(15);
$pdf->Cell(7);
$pdf->MultiCell(28,6,"Nom et qualit� \n du signataire");


$pdf->SetFont('Arial','',10);
$pdf->Ln(-12);
$pdf->Cell(75);
$pdf->MultiCell(28,6,"Cachet \n de l'entreprise");

$pdf->Ln(-12);
$pdf->Cell(140);
$pdf->MultiCell(48,6,"Signature et cachet \n du Forum Est-Horizon");


$pdf->Output("factures/".$_POST["name"].".pdf","F");
$pdf->Output();

$n = 0 ;
while ($n <= 10) {
	$n++;

	include( "phpmailer/class.phpmailer.php");// LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';

	$mail = new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->From = 'forum@est-horizon.com';
	$mail->FromName = 'Forum Est-Horizon';
	$mail->Subject = utf8_encode('R�servation de '.$_POST["name"]."");
	$mail->Body = "Yo Alice,<br /><br /> encore des petits billets � ajouter � ta tresorerie. ".utf8_decode($_POST["name"]) .utf8_encode("qui a t�l�charg� sa r�servation.<br /><br />");
	$mail->AddAttachment("factures/".$_POST["name"].".pdf",$_POST["name"].".pdf");
	$mail->IsHTML(true);
	$mail->AddAddress("tresorerie@est-horizon.com");
	$mail->Send();
	
	}


?>