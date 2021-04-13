<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author	Julien
 * @version	$Id: /apps/logistic/facture.php 0000 22-09-2011 Julien $
 */

require_once HELPERS_DIR.'fpdf/fpdf.php';

class PDF extends FPDF {

	function Head($data)
	{
		// Logo
		$this->Image(THEMES_DIR.'/feh/images/logo_hcard.png',10,6,30);
		// Décalage à droite
		
		$w = array(90,100);
		// Titre
		$this->SetFont('Arial','B',15);
        $this->Cell(35);
		$this->Cell($w[0],6,'Forum Est-Horizon',0,0,'L');
        $this->SetFont('','B',20);
		$this->Cell(0,6,'FACTURE',0,0,'C');
		$this->SetFont('','',12);
		$this->Ln();
        $this->Cell(35);
		$this->Cell($w[0],6,'Campus Artem',0,0,'L');		
		$this->Ln();
        $this->Cell(35);
		$this->Cell($w[0],6,'54042 NANCY CEDEX',0,0,'L');
		$this->Ln(23);
		
		$this->SetFont('Arial','B',15);
		$this->Cell($w[0]);
		$this->MultiCell($w[1],6,utf8_decode($data['name']),0,'L',0);
		$this->SetFont('','',12);
		$this->Cell($w[0]);
		$this->MultiCell($w[1],6,utf8_decode($data['adress']),0,'L',0);
		$this->Cell($w[0]);
		$this->MultiCell($w[1],6,utf8_decode($data['postal_code']." ".$data['city']." ".$data['country']),0,'L',0);
		
		// Saut de ligne
		$this->Ln(10);
		
		$this->SetFillColor(0,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		
		$w = array(50,50,50);
		$header = array(utf8_decode('Numéro de facture'),utf8_decode('Date de facturation'),utf8_decode('Echéance'));
		
		for($i=0;$i<count($header);$i++) {
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		}
		$this->Ln();
		
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		
		$this->Cell($w[0],6,$data['ref'],'LR',0,'C',false);
		$this->Cell($w[1],6,$data['date'],'LR',0,'C',false);
		$this->Cell($w[2],6,$data['echeance'].' jours','LR',0,'C',false);
		
		$this->Ln();
		$this->Cell(array_sum($w),0,'','T');
		
		$this->Ln(10);
	}

	// Pied de page
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
        $this->SetLeftMargin(0);
        $this->SetRightMargin(0);
		$this->SetY(-15);
		// Police Arial italique 8
		$this->SetFont('Arial','I',8);
        $this->Cell(0,6,utf8_decode('FORUM EST-HORIZON - Campus Artem 54042 NANCY CEDEX'),0,0,'C');
        $this->Ln(3);
        $this->Cell(0,6,utf8_decode('www.est-horizon.com - forum@est-horizon.com'),0,0,'C');
        $this->Ln(3);
        $this->Cell(0,6,utf8_decode('Tél. : +33 (0)3 55 66 27 13 '),0,0,'C');
        $this->Ln(3);
		$this->Cell(0,6,utf8_decode('Association de loi 1901 - Siret : 38795354000011 - APE : 748J - TVA Intra. : FR21 3897953540'),0,0,'C');
	}

	function DetailFactTable($header,$data) {
		$this->SetFillColor(0,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		
		$w = array(80,35,30,40);
		$w_sstotal = array(145,40);
		
		for($i=0;$i<count($header);$i++) {
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		}
		$this->Ln();
		
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		
		$fill=false;
		
		$this->Cell($w[0],6,'Stand '.$data['stand'][0]['designation'],'LR',0,'L',$fill);
		$this->Cell($w[1],6,number_format($data['stand'][0]['prix_each'],2,',',' '),'LR',0,'R',$fill);
		$this->Cell($w[2],6,number_format($data['stand'][0]['nombre'],0,',',' '),'LR',0,'C',$fill);
		$this->Cell($w[3],6,number_format($data['stand'][0]['prix_total'],2,',',' '),'LR',0,'R',$fill);
		$this->Ln();
		$fill = !$fill;
		
		$this->SetFont('','I');
		foreach($data['reductions']['stand'] as $row) {
			$this->Cell($w_sstotal[0],6,"dont ".$row['nom']." de ".$row['taux']."% sur le stand",'LR',0,'R',true);
			$red = -$data['stand'][0]['prix_total']*$row['taux']/100;
			$data['stand'][0]['prix_total'] += $red;
			$data['stand'][0]['prix_total'] = round($data['stand'][0]['prix_total']*100)/100;
			$this->Cell($w_sstotal[1],6,number_format($red,2,',',' '),'LR',0,'R',true);
			$this->Ln();
		}
		
		$this->SetFont('','B');
		$this->SetFillColor(110,110,110);
		$this->SetTextColor(255);
		$this->Cell($w_sstotal[0],6,"Sous-total Stand :",'LR',0,'R',true);
		$this->Cell($w_sstotal[1],6,number_format($data['stand'][0]['prix_total'],2,',',' '),'LR',0,'R',true);
		$this->Ln();
		$fill = false;
		
		$this->SetFont('');
		
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		
		$tot_lot = 0;
		
		foreach($data['mobilier'] as $row) {
			$this->Cell($w[0],6,$row['designation'],'LR',0,'L',$fill);
			$this->Cell($w[1],6,number_format($row['prix_each'],2,',',' '),'LR',0,'R',$fill);
			$this->Cell($w[2],6,number_format($row['nombre'],0,',',' '),'LR',0,'C',$fill);
			$this->Cell($w[3],6,number_format($row['prix_total'],2,',',' '),'LR',0,'R',$fill);
			$this->Ln();
			$fill = !$fill;
			$tot_lot += $row['prix_total'];
		}
		
		$this->SetFont('','I');
		foreach($data['reductions']['mobilier'] as $row) {
			$this->Cell($w_sstotal[0],6,"dont ".$row['nom']." de ".$row['taux']."% sur le mobilier",'LR',0,'R',true);
			$red = -$tot_lot*$row['taux']/100;
			$tot_lot += $red;
			$tot_lot = round($tot_lot*100)/100;
			$this->Cell($w_sstotal[1],6,number_format($red,2,',',' '),'LR',0,'R',true);
			$this->Ln();
		}
		
		$this->SetFont('','B');
		$this->SetFillColor(110,110,110);
		$this->SetTextColor(255);
		$this->Cell($w_sstotal[0],6,"Sous-total Mobilier :",'LR',0,'R',true);
		$this->Cell($w_sstotal[1],6,number_format($tot_lot,2,',',' '),'LR',0,'R',true);
		$this->Ln();
		$fill = false;
		
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		
		$tot_opt = 0;
		$this->SetFont('');
		foreach($data['options'] as $row) {
			$this->Cell($w[0],6,$row['designation'],'LR',0,'L',$fill);
			$this->Cell($w[1],6,number_format($row['prix_each'],2,',',' '),'LR',0,'R',$fill);
			$this->Cell($w[2],6,number_format($row['nombre'],0,',',' '),'LR',0,'C',$fill);
			$this->Cell($w[3],6,number_format($row['prix_total'],2,',',' '),'LR',0,'R',$fill);
			$this->Ln();
			$fill = !$fill;
			$tot_opt += $row['prix_total'];
		}
		
		$this->SetFont('','I');
		foreach($data['reductions']['options'] as $row) {
			$this->Cell($w_sstotal[0],6,"dont ".$row['nom']." de ".$row['taux']."% sur les options",'LR',0,'R',true);
			$red = -$tot_lot*$row['taux']/100;
			$tot_opt += $red;
			$tot_opt = round($tot_opt*100)/100;
			$this->Cell($w_sstotal[1],6,number_format($red,2,',',' '),'LR',0,'R',true);
			$this->Ln();
		}
		
		$this->SetFillColor(110,110,110);
		$this->SetTextColor(255);
		$this->SetFont('','B');
		$this->Cell($w_sstotal[0],6,"Sous-total Options :",'LR',0,'R',true);
		$this->Cell($w_sstotal[1],6,number_format($tot_opt,2,',',' '),'LR',0,'R',true);
		$this->Ln();
		$fill = false;
		
		$total = $data['stand'][0]['prix_total']+$tot_lot+$tot_opt;
		
		$this->SetFont('','I');
		foreach($data['reductions']['tout'] as $row) {
			$this->Cell($w_sstotal[0],6,"dont ".$row['nom']." de ".$row['taux']."% sur tout",'LR',0,'R',true);
			$red = $total*(1-$row['taux']/100);
			$red = round($red*100)/100;
			$this->Cell($w_sstotal[1],6,number_format($red-$total,2,',',' '),'LR',0,'R',true);
			$total = $red;
			$this->Ln();
		}

		if($total!=$data['price']) {
		    WNote::error("Incohérence des tarifs", "Le tarif calculé (".$total."€) ne correspond pas au tarif annoncé à l'entreprise lors de la réservation du stand (".$data['price']."€).", 'assign');
		}
        	
        	if ($data['remise'] > 0) {
			$this->Cell($w_sstotal[0],6,"dont Remise de ".$data['remise']."% sur tout",'LR',0,'R',true);
			$red = -$total*$data['remise']/100;
			$total += $red;
			$total = round($total*100)/100;
			$this->Cell($w_sstotal[1],6,number_format($red,2,',',' '),'LR',0,'R',true);
			$this->Ln();
		}
        	
		
		$this->SetFillColor(0,0,0);
		$this->SetFont('','BI');
		$this->Cell($w_sstotal[0],6,"TOTAL TTC* :",'LR',0,'R',true);
		$this->SetFillColor(254,0,0);
		$this->Cell($w_sstotal[1],6,number_format($total,2,',',' ').chr(128),'LR',0,'R',true);
		$this->Ln();
		
		$this->Cell(array_sum($w),0,'','T');
		
		$this->Ln(5);
		$this->SetFont('','I',9);
		$this->SetTextColor(0);
		$this->Cell(0,6,utf8_decode('* Le Forum Est-Horizon est de par la loi dispensé de collecter la TVA'),0,0,'L');

		$this->Ln(10);
		$this->SetFont('','B',15);
		$this->SetTextColor(0);
		$this->Cell(0,6,'NET A PAYER TTC : '.number_format($total,2,',',' ').chr(128),0,0,'R');

        $this->Ln();
		$this->SetFont('Arial','',12);
		$this->SetLeftMargin(20);
		$this->Cell(0,6,utf8_decode('Mode de paiement : chèque ou virement'),0,0,'L');
		$this->Ln(10);
        $this->SetLeftMargin(0);
        $this->SetRightMargin(0);
		$this->Cell(0,6,utf8_decode('Valeur en votre aimable règlement'),0,0,'C');
		$this->SetFont('Arial','B',12);
		$this->Ln(10);
		$this->SetLeftMargin(130);
        $this->SetRightMargin(10);
		$this->Cell(0,6,utf8_decode('Jérome AZZOLA'),0,0,'C');
		$this->Ln();
		$this->Cell(0,6,utf8_decode('Trésorier du Forum Est-Horizon'),0,0,'C');
		$this->Ln();
		$this->Cell(0,6,utf8_decode('06 58 53 08 19'),0,0,'C');
        $this->Ln();
	}
}

?>
