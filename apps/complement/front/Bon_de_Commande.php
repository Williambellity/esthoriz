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
$pdf->Cell(50,10,"Objet : Forum Est-Horizon 2015 "); 
$pdf->Ln(10);
$pdf->SetFont('Arial','',11);
$pdf->Cell(5);
$pdf->Cell(50,10,"Jeudi 22 Octobre 2015"); 
$pdf->Ln(15);
$pdf->Cell(27);
$pdf->Cell(50,10,"Madame, Monsieur,"); 
$pdf->Ln(12);
$pdf->MultiCell(180,6,"               Nous avons bien noté que vous envisagiez de participer à la 32e édition du Forum Est-Horizon qui aura lieu le jeudi 22 octobre 2015 au Centre Prouvé de Nancy. Nous nous réjouissons à la perspective de vous accueillir à ce salon professionnel de rencontre entre étudiants et entreprises. ");
$pdf->Ln(7);
$pdf->Cell(27);
$pdf->Cell(180,6,"Pour finaliser votre inscription, nous vous demandons de régler l’intégralité de votre ");
$pdf->Ln(6);
$pdf->Cell(7);
$pdf->Cell(180,6,"commande avant le 12 octobre 2015. ");
$pdf->SetFont('Arial','B',11);
$pdf->Cell(-115);
$pdf->Cell(50,6,"Votre inscription sera entérinée après réception de la totalité ");
$pdf->Ln(6);
$pdf->Cell(7);
$pdf->Cell(50,6,"du solde.  ");
$pdf->Ln(8);
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(180,6,"                 En cas de prestations supplémentaires délivrées le jour du Forum, une facture spécifique vous sera adressée après l’évènement. ");
$pdf->Ln(6);
$pdf->Cell(20);
$pdf->Cell(50,6,"Le paiement se fera :");
$pdf->Ln(7);
$pdf->Cell(7);
$pdf->Cell(50,6,"- soit par chèque à l’ordre du Forum Est-Horizon ;");
$pdf->Ln(7);
$pdf->Cell(7);
$pdf->Cell(50,6,"- soit par virement (RIB téléchargeable dans la rubrique « Vos Outils »).");
$pdf->Ln(10);
$pdf->MultiCell(180,6,"               Vous trouverez ci-dessous le Formulaire d’Inscription et le Bon de Commande correspondant à votre demande. Si vous le souhaitez, vous pouvez encore modifier votre commande et  la description de l’entreprise à partir de la rubrique « Vos Outils » depuis votre compte entreprise sur notre site : www.est-horizon.com.  Nous vous prions ensuite de nous retourner le Formulaire d’Inscription et le Bon de Commande signés, soit par courriel à forum@est-horizon.com soit par courrier à l’adresse suivante : ");
$pdf->Cell(20);
$pdf->MultiCell(60,6," Forum Est-Horizon \n Campus ARTEM \n 54042 Nancy Cedex");
$pdf->Ln(4);
$pdf->MultiCell(180,6,"                 Dans l’attente de votre réponse, nous vous prions d’agréer l’expression de nos salutations\n distinguées.");




/*-------------------------------------------
		PAGE 2
----------------------------------------------*/

$pdf->AddPage();
$pdf->SetY(50);
$pdf->SetFont('Arial','BU',20);
$pdf->Cell(50);
$pdf->Cell(50,10,"Formulaire d’inscription"); 
$pdf->Ln(16);
$pdf->SetFont('Arial','U',16);
$pdf->Cell(5);
$pdf->Cell(50,10,"Forum Est-Horizon 2015, le 22 octobre au Centre Prouvé à Nancy"); 
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
$pdf->Cell(80,18,"Adresse du siège social",1,1); 
$pdf->Ln(-18);
$pdf->Cell(85);
$pdf->Cell(80,18,"",1,1); 
$pdf->Cell(5);
$pdf->MultiCell(80,5,"Adresse de facturation \n (si l’adresse de facturation n’est pas \n renseignée, la facture sera envoyée par \n défaut à l’adresse de l’entreprise) \n ",1,1); 
$pdf->Ln(-25);
$pdf->Cell(85);
$pdf->Cell(80,25,"",1,1); 

$pdf->Cell(5);
$pdf->Cell(80,8,"Responsable pour le Forum",1,1); 
$pdf->Ln(-8);
$pdf->Cell(85);
$pdf->Cell(80,8,"",1,1); 

$pdf->Cell(5);
$pdf->Cell(80,8,"Numéro de téléphone",1,1); 
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
$pdf->MultiCell(180,6,"                  Veuillez vérifier l'exactitude des informations précédentes et les corriger ou les compléter le cas échéant (en particulier l'adresse de facturation). "); 
$pdf->Ln(4);

$pdf->MultiCell(180,6,"                  La signature du présent formulaire entraîne pour le signataire représentant ".$_POST["name"]." toutes les prescriptions du règlement et du mode d’emploi du Forum Est-Horizon dont il déclare avoir pris connaissance.");

$pdf->Ln(4);

$pdf->Cell(5);
$pdf->MultiCell(180,6,"                  En particulier, selon l’article 6 du règlement, en cas de rupture du contrat de la part de l’exposant, celui-ci s’engage à verser le solde dudit contrat. "); 
$pdf->Ln(4);

$pdf->Cell(5);
$pdf->MultiCell(180,6,"                  L’inscription de votre entreprise au Forum Est-Horizon ne sera prise en compte que par retour du présent Formulaire d'Inscription et du Bon de Commande signés. "); 
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
$pdf->Cell(40);
$pdf->Cell(50,10,"Bon de commande");

$pdf->SetFont('Arial','',12);
$pdf->Ln(12);
$pdf->Cell(5);
$pdf->Cell(50,10,"Commande n°2015 - ". $_POST["no"]);

$pdf->SetFillColor(196,172,77);
$pdf->Ln(12);
$pdf->Cell(7);
$pdf->Cell(40,10,"Désignation",1,1,'C',1);
$pdf->Ln(-10);
$pdf->Cell(47);
$pdf->Cell(40,10,"Prix unitaire (€)",1,1,'C',1);
$pdf->Ln(-10);
$pdf->Cell(87);
$pdf->Cell(40,10,"Quantité",1,1,'C',1);

$pdf->Ln(-10);
$pdf->Cell(127);
$pdf->Cell(40,10,"Total(€)",1,1,'C',1);

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
$pdf->MultiCell(40,5,"Publicité en regard de la page de présentation ",1,1);
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
	$pdf->MultiCell(40,5,"Publicité sur la 2e page de couverture ",1,1);
else
	$pdf->MultiCell(40,5,"Publicité sur la 3e page de couverture ",1,1);
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
$pdf->MultiCell(40,5,"Logo de l’entreprise sur le sac distribué aux visiteurs",1,1);
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
$pdf->Cell(50,10,"*Le Forum Est-Horizon est de par la loi dispensé de collecter la TVA.");
$pdf->Ln(12);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(87);
$pdf->Cell(50,10,"NET A PAYER TTC :  ");


$pdf->Cell(15);
$pdf->Cell(40,10,$total. " €");


$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(0);
$pdf->Cell(50);
$pdf->Cell(50,10,"Valeur en votre aimable règlement");


$pdf->Ln(10);
$pdf->Cell(7);
$pdf->Cell(50,10,"Le paiement doit être effectué avant le 12 octobre 2015, au plus tard.");
$pdf->SetFont('Arial','',10);

$pdf->Ln(18);
$pdf->Cell(10);
$pdf->MultiCell(68,6,"Formulaire d'Inscription et Bon de Commande à retourner daté et signé avec la mention « Bon pour accord »");

$pdf->SetFont('Arial','B',12);
$pdf->Ln(-17);
$pdf->Cell(90);
$pdf->MultiCell(80,6,"                 Nathan Joubert \n Trésorier du Forum Est-Horizon 2014\n                 06 32 13 64 90 \n   nathan.joubert@mines-nancy.org\n",1,1);


$pdf->SetFont('Arial','',10);
$pdf->Ln(15);
$pdf->Cell(7);
$pdf->MultiCell(28,6,"Nom et qualité \n du signataire");


$pdf->SetFont('Arial','',10);
$pdf->Ln(-12);
$pdf->Cell(75);
$pdf->MultiCell(28,6,"Cachet \n de l'entreprise");

$pdf->Ln(-12);
$pdf->Cell(140);
$pdf->MultiCell(48,6,"Signature et cachet \n du Forum Est-Horizon");


$pdf->Output("factures/".$_POST["name"].".pdf","F");
$pdf->Output();

	include( "phpmailer/class.phpmailer.php");// LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
//$dest =array("alexandre.david@outlook.fr","alexandre.david3@etu.univ-lorraine.fr","theo.michel8@etu.univ-lorraine.fr");
$dest =array("alexandre.david3@etu.univ-lorraine.fr");//,"jules@alphatango.fr","cap.baron@gmail.com","vincent.laurent2@etu.univ-lorraine.fr","arnaud.genty4@etu.univ-lorraine.fr","julien.jaxel@noos.fr","quentindebus@wanadoo.fr","redwanbouizi7@gmail.com","adribignon@gmail.com","nicolas.bouchet2@etu.univ-lorraine.fr","helen.jund@orange.fr","imanebelidan@gmail.com","djamel.moussaoui5@etu.univ-lorraine.fr","alicia.frantz4@etu.univ-lorraine.fr","bruna.valerie@gmail.com","cecile-franck@live.fr","pallarajordan@gmail.com","olivierbouville@orange.fr","abderrahman.elghazi@mines-nancy.org","alexis.rcarrassat@gmail.com","hammami.oumayma94@gmail.com","hamza.bencheikh2@etu.univ-lorraine.fr","charlotte.francois5@free.fr","louxremi@hotmail.fr","william.dangleant@live.fr","aurelien.chenier@mines-nancy.org","claire.girardeau7@etu.univ-lorraine.fr","kadiexpert@gmail.com","gficheux@hotmail.fr","guerbab.ismail@gmail.com","yassir.sef@gmail.com","maxime.palmieri@gmail.com","amine.el.aissaoui1@gmail.com","sonia-bh@live.com","jallalb@outlook.com","romainhuet.44@gmail.com","jattiouima@gmail.com","wajdi.benmessaoud@yahoo.com","collenotguillaume@gmail.com","bensadiaamine@gmail.com","corentinvc@gmail.com","alice.delmas@hotmail.fr","francois.kremer6@etu.univ-lorraine.fr","francois.dupre1@etu.univ-lorraine.fr","simon.clinquart@gmail.com","azermimouni@outlook.com","piralla.maxime@gmail.com","vouillaume.benjamin@gmail.com","reda.naciri.1@gmail.com","ibtissam.bitissa@gmail.com","e.corvoisier@gmail.com","jordane.lhomel@viacesi.fr","pedrosilvalima@hotmail.com","ulve.moea@gmail.com","d.bouguenna@laposte.net","idriss.elhassani@hotmail.com","yoann.chevalier@mines-nancy.org","raffaele.tallarico4@etu.univ-lorraine.fr","mathilde.algan@gmail.com","charles-antoine.aledo@mines-nancy.org","zakaria.naji13@gmail.com","j.cheikhmoussa@live.fr","maaroufaa@gmail.com","dimitrisimo@hotmail.com","charles-antoine.aledo@mines-nancy.org","guillaume.michard9@etu.univ-lorraine.fr","khouzmohamed@gmail.com","yannisbr@live.fr","nicolas.anglade@mines-nancy.org","matthieu.cappe@gmail.com","alexis.lartigue@mines-nancy.org","lucie.gerard.madrid@gmail.com","brendan.guillemot@orange.fr","florian.mancini@ensem.org","radwan94@hotmail.com","laurent.martignon8@etu.univ-lorraine.fr","ophely.savary6@etu.univ-lorraine.fr","caroline.lee5@etu.univ-lorraine.fr","ranaivon.al@gmail.com","nadaamaioua@gmail.com","hannequin.alexandre@gmail.com","nmouha@live.fr","poindron.florent@gmail.com","mohamed.hraisha1@etu.univ-lorraine.fr","ajouanarene@yahoo.fr","Francois.devey@gmail.com","baptiste.teissedre@gmail.com","fachethibaud@gmail.com","issam-benmekki@hotmail.fr","mhmtzclk@hotmail.fr","youssefbouymajjane1@gmail.com","nicolas_jukic@hotmail.fr","dur.ebru@yahoo.fr","amine.azeroil@gmail.com","ismail.berrada@myicn.fr","philippe.jacquemot@ensem.org","andre.peyraud@gmail.com","a.felomaki@yahoo.fr","jason.pennaneach@myicn.fr","juliette.lefebvre9@etu.univ-lorraine.fr","anthonyspina@hotmail.fr","deforge.nicoo@gmail.com","aless.quinto11@gmail.com","christophecarlier.info@gmail.com","tamburello.maxime@gmail.com","madegryse@hotmail.fr","oudina.kheira@gmail.com","kossi.aboudou@myicn.fr","lucas.winkelmann9@etu.univ-lorraine.fr","oufrid.karim@gmail.com","tong.xuheng@gmail.com","angelicavargas89@hotmail.com","kossi.aboudou@myicn.fr","emilie.ung@live.fr","heloise.yvroud@live.fr","lasserre.arnaud@hotmail.fr","saro.michael3@gmail.com","meryem-anedam@hotmail.fr","stanislas.marques@hotmail.fr","maeva.fredal@myicn.fr","ghislain-rouzier@orange.fr","rhitalami@gmail.com","mana.rarison@gmail.com","yayun.x@hotmail.com","sarah.ganter@orange.fr","alexandre.tokka@gmail.com","alexis.rcarrassat@gmail.com","kossi.aboudou@myicn.fr","josephine.bay@myicn.fr","abderrahmanskiredj@hotmail.com","camille.sartori@gmail.com","camille.eischen7@etu.univ-lorraine.fr","thomas.bazzucchi@gmail.com","donovan.robin572011@gmail.com","ugo.borne3@gmail.com","clement.padoux@me.com","lydia.goumeziane@live.fr","laura.misrachi@gmail.com","johanna.fochi@live.fr","johanna.fochi@live.fr","ccoupe@live.fr","sarah.anson@outlook.com","joanna.lutton@gmail.com","antoine.michel9@etu.univ-lorraine.fr","elomari.fatima95@gmail.com","aness.amr@gmail.com","gthomario@yahoo.com","emilie.ung@live.fr","juw.zanini@gmail.com","aurelien.comoy@mines-nancy.org","ael.ragonnet@gmail.com","vial-etienne@orange.fr","pierrearthur@me.com","mlissa.m.p@gmail.com","amine.chelgham@yahoo.fr","zyrae.7@gmail.com","alexandre.gingembre@sfr.fr","valentin.ruzic@myicn.fr","elisefer@orange.fr","elisefer@orange.fr","ayoub.lerhzaouni4@etu.univ-lorraine.fr","morgansans@hotmail.fr","antoineblattner@free.fr","mercier1193@gmail.com","romainpipaud@yahoo.fr","yongzheng.pan@myicn.fr","thomas.georges1@univ-lorraine.fr","joanne.lasalmoniebourion@gmail.com","boudabsalotfi@live.fr","mehdisoufiani1@gmail.com","younesstourtit@gmail.com","antoine.brichler@gmail.com","etiennesk8@hotmail.com","tahahamdouli@gmail.com","rania.ellaia@gmail.com","h.guichard@hotmail.fr","mohammed.hseini5@etu.univ-loraine.fr","jihaneelhamzaoui@gmail.com","jbdouriez@hotmail.fr","andreaviard@sfr.fr","mohamed.mabrouk@gmx.fr","leo.ferriere@free.fr","neimardamelie@gmail.com","selkhiam@gmail.com","boudhraa.mehdi@gmail.com","demarchi.dias@gmail.com","selim.zidi@mines-nancy.org","hugo.renaudin9@etu.univ-lorraine.fr","phynicia.b@gmail.com","bjmaiel@yahoo.fr","huanglei1994@gmail.com","alvin.mullai01@gmail.com","ali-rebai1@outlook.fr","florence-thouvenin@sfr.fr","arnaud.genty4@etu.univ-lorraine.fr","yassine.adil.1991@gmail.com","fatnassi.marwen@gmail.com","lauramorieux@laposte.net","bourgeois.angelique@gmail.com","cyprien.gbl@gmail.com","marine.gregoire@hotmail.fr","loragazzini@gmail.com","edouard.bontems@ensem.org","anthonyspina@hotmail.fr","tijs.josselin@gmail.com","jill.galland@myicn.fr","cecile.bouvet@myicn.fr","anthonyspina@hotmail.fr","anais.ducourt@gmail.com","kaizer.margaux@gmail.com","antoine.pingault@hotmail.fr","danieljm22@gmail.com","adrienjcq@gmail.com","camelmeridja@gmail.com","mehdi121993@hotmail.fr","michel.tjm@gmail.com","quente.59@hotmail.fr","olla.anthony@gmail.com","aurore-pelzer@hotmail.fr","Roland.nicolas57@gmail.com","anthonyspina@hotmail.fr","chloe.lauraire@etu.univ-lorraine.fr","eutychusfr@yahoo.fr","vincentagogue@yahoo.fr","agmehdi@hotmail.fr","antony.sporm@gmail.com","joel.houenou@myicn.fr","bigel-christophe@bbox.fr","matthieu.riff@gmail.com","morgansans@hotmail.fr","GUENNOUN.SOUKAINA@GMAIL.COM","guillaume.mas1@etu.univ-lorraine.fr","c.alleyrat@gmail.com","sylvain.adolf@gmail.com","gregoire.jeannerod@hotmail.fr","julien.coyard@gmail.com","justine.motroni@gmail.com","jncolson@orange.fr","Stephanie.ngounve@myicn.fr","barthelemy.fournier@gmail.com","max.steinn@gmail.com","charlesmartinon@gmail.com","anissa.sire@myicn.fr","daniel.scortatore@gmail.com","lise.p@hotmail.fr","celia.liebel@myicn.fr","gustave.burguet@myicn.fr","sergeantstephane@gmail.com","haidakamal@yahoo.fr","eleonore.degalzain@myicn.fr","paul.deronne@myicn.fr","clairedesportes@hotmail.com","l.balestriero@gmail.com","alberterwan@yahoo.fr","anais.marteau93@gmail.com","rodolphe.mathieu@hotmail.fr","monchablon.clement@gmail.com","charlesagbessi@gmail.com","faradi.sigmaexcel@gmail.com","mouhsinejeliban@hotmail.fr","mariabuenoserrano@gmail.com","jeremyclavaud@gmail.com","justelucie@yahoo.fr","olivierbettoni@hotmail.com","olivierbettoni@hotmail.com","omar.metal.2015@gmail.com","fermon.david@gmail.com","a.machting@gmail.com","clementfodjo@yahoo.fr","nelson.joubert@gmx.fr","thomas.jedrecy@gmail.com","edouardli@aol.com","vincent.baer@telecomnancy.net","paul.bonthoux@gmail.com","gwenaelle.castry@hotmail.fr","elaynousse.mohammed@gmail.com","oussama.afkir@ensem.org","cinthia.botchaknoubissie@myicn.fr","achraf.belmhaidi@gmail.com","adam.saidi@myicn.fr","rania.ellaia@gmail.com","anissa.heddouche@gmail.com","baya.farouk@gmail.com","ddvralm@gmail.com","masdanmustapha@gmail.com","lia.neveux@hotmail.fr","bailloux.romain@hotmail.fr","thiebaut.chloe@gmail.com","hor.michel@hotmail.fr","jonathan.blandin3@gmail.com","berthelinbastien@yahoo.fr","arthurdu88@hotmail.com","bisson.chris@neuf.fr","jeremy.guichard@supelec.fr","maxime.blaise@outlook.fr","schaller.emilie@live.fr","mathieu.humbert@gmail.Com","sarah.soares2@etu.univ-lorraine.fr","abibfa01@gmail.com","ugo.moquay@gmail.com","chafi10aziz@gmail.com","alexandremari06@gmail.com","belbachir.roumeyla@gmail.com","essarhani.mustapha@gmail.com","justine.godefroy@orange.fr","e.thierry-laumont@hotmail.fr","lucie.d-alguerre5@etu.univ-lorraine.fr","pls2.android@gmail.com","mathias.mellinger@gmail.com","bouaafia.anass@gmail.com","elfarjimarwane@gmail.com","samba.tembely@gmail.com","lea.camara@live.fr","agmehdi@hotmail.fr","phialyarthur@gmail.com","chawki-benkadja@outlook.fr","malmoisso@hotmail.com","andres.blanche@gmail.com","camille.schneider9@etu.univ-lorraine.fr","e.thierry-laumont@hotmail.fr","martin.cho@hotmail.fr","simon.fauconnier@ecam-strasbourg.eu","pauline.gineste4@etu.univ-lorraine.fr","amine.elamimri@gmail.com","jerome.rollinger@gmail.com","Mellinger.nicolas@gmail.com","mamadoudia2005@hotmail.com","franck.nowicki@gmail.com","mamadoudia2005@hotmail.com","davy.tauzin@gmail.com","anouar.marouf@myicn.fr","c_mala@hotmail.fr","lasserre.arnaud@hotmail.fr","rei.mizuno@laposte.net","lya.lugon-cornejo-vom-marttens4@etu.univ-lorraine.fr","julian.morel@myicn.fr","emmanuelle.mazon@myicn.fr","islemaziz@yahoo.fr","william.berg@laposte.net","monniermat@sfr.fr","tribout.g@gmail.com","ollinger@ualberta.ca","augustin.sebaux@mines-nancy.org","laurepascalehamet@gmail.com","celine-dubost@hotmail.fr","celine-dubost@hotmail.fr","abderrahmanskiredj@hotmail.com","yassine.assaadi@gmail.com","mebounouphilippe@gmail.com","jbdouriez@hotmail.fr","klingsor.fouassouofopossi@myicn.fr","mathieu.jaffre@mines-nancy.org","attabarycelia@gmail.com","elmrabet.yassine@gmail.com","stephanie.nguemowandji@myicn.fr","marcos.maldonado@usach.cl","gbbenoit@live.fr","loriane.schmidt@myicn.fr","anais.carmet@orange.fr","romain.papelier@gmail.com","jessy.vicente@outlook.com","zhige.wang2@etu.univ-lorraine.fr","cecilia_louis@orange.fr","yasser.aissaoui@gmail.com","yasser.aissaoui@gmail.com","kossi.aboudou@myicn.fr","margot.bonmarin@live.fr","jereleto@gmail.com","julien.martellina@gmail.com","diattoud@gmail.com","achraftamtalini84@gmail.com","camille.boutinet@mines-nancy.org","ablatia@hotmail.fr","adele-maimouna.diatta@myicn.fr","sofiarachad1@gmail.com","thibault.lebourdiec@mines-nancy.org","julie.duarte@myicn.fr","elena.persavalli@myicn.fr","annick.thimon@univ-lorraine.fr","liselauvergeon@gmail.com","bahi.meslem@gmail.com","christopher.bourgel@laposte.net","jason.gombert@gmail.com","lejdazara@gmail.com","eva.lescroart@live.fr","azermimouni@outlook.com","matteo.carrozzo@virgilio.it","niecieckialexandre@gmail.com","erasmomarcosano@gmail.com","hamou.aldja.kahina@gmail.com","ouazenekatib.enp@gmail.com","maxime.mautray@free.fr","laetitia.leluherne@free.fr","maureen.rivet@gmail.com","valentin.calisti1@etu.univ-lorraine.fr","oumi.wahabi@gmail.com","jdparra75@gmail.com","gpawindealexis@gmail.com","rjs_157@hotmail.com","qi.yaostv@gmail.com","jc.haubensack@gmail.com","claptien.corentin@gmail.com","ondine.lamy@telecomnancy.net","mohamed.el-aqqad@mines-nancy.org","pierre-c.antonini@laposte.net","daviddesouza@free.fr","imad.lazrak@myicn.fr","matthieu.ivaldi@gmail.com","prochowskii.anthony@hotmail.fr","estelle.lamant@sfr.fr","estelle.lamant@sfr.fr","lucasmpr@gmail.com","louis.marie.donze@gmail.com","clement.himburg@gmail.com","imen.zaied@myicn.fr","mostafa.taounte@gmail.com’ , ‘ludovic.castellottix@etu.univ-lorraine.fr","sophie.pettazzoni5@etu.univ-lorraine.fr","franck.jolly6@etu.univ-lorraine.fr","mohammed seddik.rouibah@etu.univ-lorraine.fr","baptiste.taron@etu.univ-lorraine.fr","françoise.michel@etu.univ-lorraine.fr","damien.husson@etu.univ-lorraine.fr","julien.bonnet@etu.univ-lorraine.fr","daniel.plantin@etu.univ-lorraine.fr","mathieu.chatton@etu.univ-lorraine.fr","edouard.genetay6@etu.univ-lorraine.fr","raphael.cauhape9@etu.univ-lorraine.fr","quentin.ludemann1@etu.univ-lorraine.fr","sofiann.chehaibou@etu.univ-lorraine.fr","carole.eber1@etu.univ-lorraine.fr","melanie.mouton@etu.univ-lorraine.fr","","atrick.samuel7u@etu.univ-lorraine.fr","chloe.mangeot@etu.univ-lorraine.fr","thomas.meyer@etu.univ-lorraine.fr","guillaume.boccas2@etu.univ-lorraine.fr","charlene.menko@etu.univ-lorraine.fr","thomas.despujol@etu.univ-lorraine.fr","charles.dumenil1@etu.univ-lorraine.fr","yara.brignon1@etu.univ-lorraine.fr","ling.zhou2@etu.univ-lorraine.fr","nicolas.jaubourg6@etu.univ-lorraine.fr","adele.robin7@etu.univ-lorraine.fr","benjamin.nicolet2@etu.univ-lorraine.fr","sandru.gunasekaram6@etu.univ-lorraine.fr","ali.el alaoui@etu.univ-lorraine.fr","kossi.aboudou@etu.univ-lorraine.fr","guillaume.faisant5@etu.univ-lorraine.fr","lucas.dupenloup2@etu.univ-lorraine.fr","hua.fan@etu.univ-lorraine.fr","yahya.naji5@etu.univ-lorraine.fr","khaoula.azzouzi@etu.univ-lorraine.fr","solène.kudey8@etu.univ-lorraine.fr","stanislas.thibault@etu.univ-lorraine.fr","renaud.vilmart2@etu.univ-lorraine.fr","eya.kalboussi1@etu.univ-lorraine.fr","ali.begdouri1u@etu.univ-lorraine.fr","hugues.macinet@etu.univ-lorraine.fr","guillaume.boccas2@etu.univ-lorraine.fr","minli.zhang@etu.univ-lorraine.fr","yufei.li3@etu.univ-lorraine.fr","yacine.chouieb@etu.univ-lorraine.fr","liaoyuan.zhao4@etu.univ-lorraine.fr","huanrong.lu12@etu.univ-lorraine.fr","lucile.merle8@etu.univ-lorraine.fr","manon.leonard6@etu.univ-lorraine.fr","thomas.pagelot8@etu.univ-lorraine.fr","jean-euds.gauer4@etu.univ-lorraine.fr","jonathan.dolbeau2@etu.univ-lorraine.fr","mouna.haloum8@etu.univ-lorraine.fr","chrays.ibombo1@etu.univ-lorraine.fr","amélie.devresse@etu.univ-lorraine.fr","samantha.feuerstein7@etu.univ-lorraine.fr","quentin.bion5@etu.univ-lorraine.fr","tom.regnier4@etu.univ-lorraine.fr","thi dung.nguyen9@etu.univ-lorraine.fr","jillian.palemot2@etu.univ-lorraine.fr","anais.ait meddout@etu.univ-lorraine.fr","hind.couki@etu.univ-lorraine.fr","walid.lallam@etu.univ-lorraine.fr","raphaël.siméon4@etu.univ-lorraine.fr","malik.nezar@etu.univ-lorraine.fr","marc.reichert@etu.univ-lorraine.fr","mohammed-seddik.rouibah@etu.univ-lorraine.fr","andrea.emili@etu.univ-lorraine.fr","mahamat.soumainé@etu.univ-lorraine.fr","ibrahima.diouf@etu.univ-lorraine.fr","mickael.jean@etu.univ-lorraine.fr","youness.chahlal@etu.univ-lorraine.fr","simon.labainville@etu.univ-lorraine.fr","alexandre.petit@etu.univ-lorraine.fr","emilie.tisserand@etu.univ-lorraine.fr","ouiam.kanberg@etu.univ-lorraine.fr","cecilia.seinturier@etu.univ-lorraine.fr","marie.schiavon@etu.univ-lorraine.fr","bachir.arif@etu.univ-lorraine.fr","serge.morel@etu.univ-lorraine.fr","menoune.arroum@etu.univ-lorraine.fr","camille.giss@etu.univ-lorraine.fr","valentina.pastore@etu.univ-lorraine.fr","gabriela.derante@etu.univ-lorraine.fr","théane.weibel@etu.univ-lorraine.fr","chloé.benichou@etu.univ-lorraine.fr","regis.boisaubert@etu.univ-lorraine.fr","thomas.heymelot@etu.univ-lorraine.fr","oscar.louapre9@etu.univ-lorraine.fr","duc vinh.nguyen@etu.univ-lorraine.fr","xuan son.nguyen@etu.univ-lorraine.fr","margaux.sancier@etu.univ-lorraine.fr","zharif.ahmad zaharil2@etu.univ-lorraine.fr","taha yacine.sebra@etu.univ-lorraine.fr","cao.hanghang@etu.univ-lorraine.fr","inasse.elmezouari@etu.univ-lorraine.fr","aline.robin6@etu.univ-lorraine.fr","nathalie.fick@etu.univ-lorraine.fr","mohamed.el jazouli7@etu.univ-lorraine.fr","romain.lallement6@etu.univ-lorraine.fr","veronique.defrain@etu.univ-lorraine.fr","david.werich7@etu.univ-lorraine.fr"," vladimir.perrin8@etu.univ-lorraine.fr","karim.elyousfi@etu.univ-lorraine.fr","emma.collignon16@etu.univ-lorraine.fr","","aul.lefevre5@etu.univ-lorraine.fr","ilias.ftouhi2@etu.univ-lorraine.fr","alexis.foult6@etu.univ-lorraine.fr","jonathan.quirot9@etu.univ-lorraine.fr","thibault.geoffroy14@etu.univ-lorraine.fr","lie.ma9@etu.univ-lorraine.fr","mathieu.strub7@etu.univ-lorraine.fr","jonas.ouazan2@etu.univ-lorraine.fr","christophe.starck7@etu.univ-lorraine.fr","fabien.mauvoisin7@etu.univ-lorraine.fr","mohammed yassine.kanache1@etu.univ-lorraine.fr","etienne.gourhand le culff6@etu.univ-lorraine.fr","loïc.will5@etu.univ-lorraine.fr","loïc.wicker7@etu.univ-lorraine.fr","manon.josserand1@etu.univ-lorraine.fr","astrid.temmerman8@etu.univ-lorraine.fr","arthur.mourey3@etu.univ-lorraine.fr","bihui.ou2@etu.univ-lorraine.fr","jean-etienne.schouver1@etu.univ-lorraine.fr","mouna.ben hafsia9@etu.univ-lorraine.fr","mamour.diop9@etu.univ-lorraine.fr","","ape makhala.mbaye6@etu.univ-lorraine.fr","tom.bedel2@etu.univ-lorraine.fr","roderick.pierre1@etu.univ-lorraine.fr","fawzi.boufatah3@etu.univ-lorraine.fr","jonathan.leroy5@etu.univ-lorraine.fr","jean baptiste.maxant@etu.univ-lorraine.fr","ibrahim.mardioui@etu.univ-lorraine.fr","","auline.daniel5@etu.univ-lorraine.fr","jeremy.anciaux@etu.univ-lorraine.fr","camille.bloch2@etu.univ-lorraine.fr","adrien.letoffe8@etu.univ-lorraine.fr","valerian.lopez5@etu.univ-lorraine.fr","thibault.jauerzac@etu.univ-lorraine.fr","etienne.cailleaux@etu.univ-lorraine.fr","gillian.ziegler4@etu.univ-lorraine.fr","mohamed saad.aakil@etu.univ-lorraine.fr","clément.biri9@etu.univ-lorraine.fr","armand.ribouillaut5@etu.univ-lorraine.fr","brieuc.berrurt@etu.univ-lorraine.fr","etienne.lerch9@etu.univ-lorraine.fr","mathieu.gerard184@etu.univ-lorraine.fr","mike.holler3@etu.univ-lorraine.fr","mohamed.noumir2@etu.univ-lorraine.fr","reda.el jarraï@etu.univ-lorraine.fr","julien.roman dias@etu.univ-lorraine.fr","kevin.autin24@etu.univ-lorraine.fr","cci.thiery célia@etu.univ-lorraine.fr","liang.li3@etu.univ-lorraine.fr","laila.chiadmi@etu.univ-lorraine.fr","charlotte.simonin@etu.univ-lorraine.fr","mathieu.hais8@etu.univ-lorraine.fr","mustapha.bousbia @etu.univ-lorraine.fr","allan.labrana@etu.univ-lorraine.fr","romain.lopes7@etu.univ-lorraine.fr","aboubacar.diaite@etu.univ-lorraine.fr","asma.haloui@etu.univ-lorraine.fr","elodie.laumoned@etu.univ-lorraine.fr","florent.biotti@etu.univ-lorraine.fr","mélanie.ducros8@etu.univ-lorraine.fr","matheiu.lamboley@etu.univ-lorraine.fr","waignan.shen@etu.univ-lorraine.fr","lucie.survier@etu.univ-lorraine.fr","anthony.stasiak@etu.univ-lorraine.fr","celien.zacharie3@etu.univ-lorraine.fr","","aul.xolin3@etu.univ-lorraine.fr","anan.xu@etu.univ-lorraine.fr","ouidad.jaouani@etu.univ-lorraine.fr",".@etu.univ-lorraine.fr","baptiste.thiberge7@etu.univ-lorraine.fr","weiwen.hu@etu.univ-lorraine.fr","monuo.luo@etu.univ-lorraine.fr","loïc.debrose@etu.univ-lorraine.fr","valentin.picard1@etu.univ-lorraine.fr","cathrine.burdy@etu.univ-lorraine.fr","annie.forignon@etu.univ-lorraine.fr","younes.aetben-ouissaden@etu.univ-lorraine.fr","dorian.boluix@etu.univ-lorraine.fr","erwan.guenier@etu.univ-lorraine.fr","","aul.caupin@etu.univ-lorraine.fr","alexandre.yvon5@etu.univ-lorraine.fr","michel.carlino6@etu.univ-lorraine.fr","virgil.perrin@etu.univ-lorraine.fr","alexis.anthonioz9@etu.univ-lorraine.fr","maxime.gamin6@etu.univ-lorraine.fr","rebecca.silva alcantara@etu.univ-lorraine.fr","roxane.dubois7@etu.univ-lorraine.fr","thibaut.saudrais2@etu.univ-lorraine.fr","sylvain.gaborit7@etu.univ-lorraine.fr","benjamin.noel@etu.univ-lorraine.fr","abdallah.houmairu1@etu.univ-lorraine.fr","guillaume.poirier@etu.univ-lorraine.fr","vincent.martin7@etu.univ-lorraine.fr","shijiao.deng15@etu.univ-lorraine.fr","louise.chaufaille3@etu.univ-lorraine.fr","henri.decauchy7@etu.univ-lorraine.fr","jeremy.bechet7@etu.univ-lorraine.fr","hamza.jabir4@etu.univ-lorraine.fr","quentin.sessini@etu.univ-lorraine.fr","mathilde.oun@etu.univ-lorraine.fr","alexandre.zirmi4@etu.univ-lorraine.fr","abdelatif.saiah1@etu.univ-lorraine.fr","mathieu.personeni5@etu.univ-lorraine.fr","clément.salmon1@etu.univ-lorraine.fr","karim.khelil2u@etu.univ-lorraine.fr","sarah.rubin5@etu.univ-lorraine.fr","nicolas.renoult5@etu.univ-lorraine.fr","baptiste.chateau8@etu.univ-lorraine.fr","imene.amalu@etu.univ-lorraine.fr","aurélie.morlon@etu.univ-lorraine.fr","tristan.neret5@etu.univ-lorraine.fr","hajar.elhedad8@etu.univ-lorraine.fr","david.michel1@etu.univ-lorraine.fr","thomas.lejeune@etu.univ-lorraine.fr","hughes alexis.georges@etu.univ-lorraine.fr","arthur.masson@etu.univ-lorraine.fr","arnaud.magne4@etu.univ-lorraine.fr","baba.faye@etu.univ-lorraine.fr","ny hakanto.ratrema@etu.univ-lorraine.fr","alexandre.david@etu.univ-lorraine.fr");
	$mail = new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->From = 'forum@est-horizon.com';
	$mail->FromName = 'Forum Est-Horizon';
	$mail->Subject = utf8_encode('Forum Est-Horizon : satisfaction visiteur');
	$mail->Body = utf8_encode('Bonjour,</br>
Vous avez participé à l’édition 2015 du Forum Est-Horizon, et nous vous en remercions.</br>
Nous vous serions très reconnaissants de bien vouloir prendre quelques instants pour remplir un</br>
 questionnaire de satisfaction en suivant le lien suivant :  <a href="http://www.est-horizon.com/quest" > Remplir le formulaire</a></br></br>
En vous remerciant d’avance,</br>
L’équipe du Forum Est-Horizon
');
	$mail->IsHTML(true);
		//for($i=0;$i<647;$i++){
		for($i=0;$i<1;$i++){
	$mail->AddAddress($dest[$i]); //ClearAddresses()
	
	$mail->Send();
	$mail->ClearAddresses();}
	
/*
						$mail->Body = utf8_encode('Vous avez participé à l’édition 2015 du Forum Est-Horizon, et nous vous en remercions.</br>
Nous vous serions très reconnaissants de bien vouloir prendre quelques instants pour remplir un</br>
 questionnaire de satisfaction en suivant le lien suivant : http://goo.gl/forms/wMt4qkQtyV </br></br>

En vous remerciant d’avance,</br>
L’équipe du Forum Est-Horizon


						');*/
						
//$dest =array("alexandre.david@outlook.fr","fst-m1-biomane@etu.univ-lorraine.fr","fst-m1-biomane-fc@etu.univ-lorraine.fr","fst-m1-biomane-fc-prec@etu.univ-lorraine.fr","fst-m1-biomane-prec@etu.univ-lorraine.fr","fst-m1-chimie@etu.univ-lorraine.fr","fst-m1-chimie-fc@etu.univ-lorraine.fr","fst-m1-chimie-fc-prec@etu.univ-lorraine.fr","fst-m1-chimie-fi@etu.univ-lorraine.fr","fst-m1-chimie-fi-prec@etu.univ-lorraine.fr","fst-m1-chimie-prec@etu.univ-lorraine.fr","fst-m1-fage@etu.univ-lorraine.fr","fst-m1-fage-fc@etu.univ-lorraine.fr","fst-m1-fage-fc-prec@etu.univ-lorraine.fr","fst-m1-fage-fi@etu.univ-lorraine.fr","fst-m1-fage-fi-prec@etu.univ-lorraine.fr","fst-m1-fage-prec@etu.univ-lorraine.fr","fst-m1-gc@etu.univ-lorraine.fr","fst-m1-gc-fc@etu.univ-lorraine.fr","fst-m1-gc-fc-prec@etu.univ-lorraine.fr","fst-m1-gc-fi@etu.univ-lorraine.fr","fst-m1-gc-fi-prec@etu.univ-lorraine.fr","fst-m1-gc-prec@etu.univ-lorraine.fr","fst-m1-gpre@etu.univ-lorraine.fr","fst-m1-gpre-fc@etu.univ-lorraine.fr","fst-m1-gpre-fc-prec@etu.univ-lorraine.fr","fst-m1-gpre-fi@etu.univ-lorrine.fr","fst-m1-gpre-fi-prec@etu.univ-lorraine.fr","fst-m1-gpre-prec@etu.univ-lorraine.fr","fst-m1-i2e2i@etu.univ-lorraine.fr","fst-m1-i2e2i-fc@etu.univ-lorraine.fr","fst-m1-i2e2i-fc-prec@etu.univ-lorraine.fr","fst-m1-i2e2i-fi@etu.univ-lorraine.fr","fst-m1-i2e2i-fi-prec@etu.univ-lorraine.fr","fst-m1-i2e2i-prec@etu.univ-lorraine.fr","fst-m1-info@etu.univ-lorraine.fr","fst-m1-info-fc@etu.univ-lorraine.fr","fst-m1-info-fc-prec@etu.univ-lorraine.fr","fst-m1-info-fi@etu.univ-lorraine.fr","fst-m1-info-fi-prec@etu.univ-lorraine.fr","fst-m1-info-prec@etu.univ-lorraine.fr","fst-m1-isc@etu.univ-lorraine.fr","fst-m1-isc-fc@etu.univ-lorraine.fr","fst-m1-isc-fc-prec@etu.univ-lorraine.fr","fst-m1-isc-fi@etu.univ-lorraine.fr","fst-m1-isc-fi-prec@etu.univ-lorraine.fr","fst-m1-isc-prec@etu.univ-lorraine.fr","fst-m1-maths@etu.univ-lorraine.fr","fst-m1-maths-fc@etu.univ-lorraine.fr","fst-m1-maths-fc-prec@etu.univ-lorraine.fr","fst-m1-maths-fi@etu.univ-lorraine.fr","fst-m1-maths-fi-prec@etu.univ-lorraine.fr","fst-m1-maths-prec@etu.univ-lorraine.fr","fst-m1-meef-maths@etu.univ-lorraine.fr","fst-m1-meef-maths-fi@etu.univ-lorraine.fr","fst-m1-meef-maths-fi-prec@etu.univ-lorraine.fr","fst-m1-meef-maths-prec@etu.univ-lorraine.fr","fst-m1-meef-spc@etu.univ-lorraine.fr","fst-m1-meef-spc-fi@etu.univ-lorraine.fr","fst-m1-meef-spc-fi-prec@etu.univ-lorraine.fr","fst-m1-meef-spc-prec@etu.univ-lorraine.fr","fst-m1-meef-svt@etu.univ-lorraine.fr","fst-m1-meef-svt-fi@etu.univ-lorraine.fr","fst-m1-meef-svt-fi-prec@etu.univ-lorraine.fr","fst-m1-meef-svt-prec@etu.univ-lorraine.fr","fst-m1-mepp@etu.univ-lorraine.fr","fst-m1-mepp-fc@etu.univ-lorraine.fr","fst-m1-mepp-fc-prec@etu.univ-lorraine.fr","fst-m1-mepp-fi@etu.univ-lorraine.fr","fst-m1-mepp-fi-prec@etu.univ-lorraine.fr","fst-m1-mepp-prec@etu.univ-lorraine.fr","fst-m1-physique@etu.univ-lorraine.fr","fst-m1-physique-fc@etu.univ-lorraine.fr","fst-m1-physique-fc-prec@etu.univ-lorraine.fr","fst-m1-physique-fi@etu.univ-lorraine.fr","fst-m1-physique-fi-prec@etu.univ-lorraine.fr","fst-m1-physique-prec@etu.univ-lorraine.fr","fst-m1-spim@etu.univ-lorraine.fr","fst-m1-spim-fc@etu.univ-lorraine.fr","fst-m1-spim-fc-prec@etu.univ-lorraine.fr","fst-m1-spim-prec@etu.univ-lorraine.fr","fst-m2-biomane-bm@etu.univ-lorraine.fr","fst-m2-biomane-bm-fc@etu.univ-lorraine.fr","fst-m2-biomane-bm-fc-prec@etu.univ-lorraine.fr","fst-m2-biomane-bm-fi@etu.univ-lorraine.fr","fst-m2-biomane-bm-fi-prec@etu.univ-lorraine.fr","fst-m2-biomane-bm-prec@etu.univ-lorraine.fr","fst-m2-biomane-bm-vae@etu.univ-lorraine.fr","fst-m2-biomane-bm-vae-prec@etu.univ-lorraine.fr","fst-m2-biomane-mes@etu.univ-lorraine.fr","fst-m2-biomane-mes-fc@etu.univ-lorraine.fr","fst-m2-biomane-mes-fc-prec@etu.univ-lorraine.fr","fst-m2-biomane-mes-fi@etu.univ-lorraine.fr","fst-m2-biomane-mes-fi-prec@etu.univ-lorraine.fr","fst-m2-biomane-mes-prec@etu.univ-lorraine.fr","fst-m2-biomane-mes-vae@etu.univ-lorraine.fr","fst-m2-biomane-mes-vae-prec@etu.univ-lorraine.fr","fst-m2-bsis-gc@etu.univ-lorraine.fr","fst-m2-bsis-gc-fc@etu.univ-lorraine.fr","fst-m2-bsis-gc-fc-prec@etu.univ-lorraine.fr","fst-m2-bsis-gc-fi@etu.univ-lorraine.fr","fst-m2-bsis-gc-fi-prec@etu.univ-lorraine.fr","fst-m2-bsis-gc-prec@etu.univ-lorraine.fr","fst-m2-bsis-gc-vae@etu.univ-lorraine.fr","fst-m2-bsis-gc-vae-prec@etu.univ-lorraine.fr","fst-m2-bsis-svt@etu.univ-lorraine.fr","fst-m2-bsis-svt-fc@etu.univ-lorraine.fr","fst-m2-bsis-svt-fc-prec@etu.univ-lorraine.fr","fst-m2-bsis-svt-fi@etu.univ-lorraine.fr","fst-m2-bsis-svt-fi-prec@etu.univ-lorraine.fr","fst-m2-bsis-svt-prec@etu.univ-lorraine.fr","fst-m2-bsis-svt-vae@etu.univ-lorraine.fr","fst-m2-bsis-svt-vae-prec@etu.univ-lorraine.fr","fst-m2-chimie-cds@etu.univ-lorraine.fr","fst-m2-chimie-cds-fc@etu.univ-lorraine.fr","fst-m2-chimie-cds-fc-prec@etu.univ-lorraine.fr","fst-m2-chimie-cds-fi@etu.univ-lorraine.fr","fst-m2-chimie-cds-fi-prec@etu.univ-lorraine.fr","fst-m2-chimie-cds-prec@etu.univ-lorraine.fr","fst-m2-chimie-cds-vae@etu.univ-lorraine.fr","fst-m2-chimie-cds-vae-prec@etu.univ-lorraine.fr","fst-m2-chimie-sams@etu.univ-lorraine.fr","fst-m2-chimie-sams-fc@etu.univ-lorraine.fr","fst-m2-chimie-sams-fc-prec@etu.univ-lorraine.fr","fst-m2-chimie-sams-fi@etu.univ-lorraine.fr","fst-m2-chimie-sams-fi-prec@etu.univ-lorraine.fr","fst-m2-chimie-sams-prec@etu.univ-lorraine.fr","fst-m2-chimie-sams-vae@etu.univ-lorraine.fr","fst-m2-chimie-sams-vae-prec@etu.univ-lorraine.fr","fst-m2-fage-bfd@etu.univ-lorraine.fr","fst-m2-fage-bfd-fc@etu.univ-lorraine.fr","fst-m2-fage-bfd-fc-prec@etu.univ-lorraine.fr","fst-m2-fage-bfd-fi@etu.univ-lorraine.fr","fst-m2-fage-bfd-fi-prec@etu.univ-lorraine.fr","fst-m2-fage-bfd-prec@etu.univ-lorraine.fr","fst-m2-fage-bfd-vae@etu.univ-lorraine.fr","fst-m2-fage-bfd-vae-prec@etu.univ-lorraine.fr","fst-m2-fage-bia@etu.univ-lorraine.fr","fst-m2-fage-bia-fc@etu.univ-lorraine.fr","fst-m2-fage-bia-fc-prec@etu.univ-lorraine.fr","fst-m2-fage-bia-fi@etu.univ-lorraine.fr","fst-m2-fage-bia-fi-prec@etu.univ-lorraine.fr","fst-m2-fage-bia-prec@etu.univ-lorraine.fr","fst-m2-fage-bia-vae@etu.univ-lorraine.fr","fst-m2-fage-bia-vae-prec@etu.univ-lorraine.fr","fst-m2-fage-fen@etu.univ-lorraine.fr","fst-m2-fage-fen-fc@etu.univ-lorraine.fr","fst-m2-fage-fen-fc-prec@etu.univ-lorraine.fr","fst-m2-fage-fen-fi@etu.univ-lorraine.fr","fst-m2-fage-fen-fi-prec@etu.univ-lorraine.fr","fst-m2-fage-fen-prec@etu.univ-lorraine.fr","fst-m2-fage-fen-vae@etu.univ-lorraine.fr","fst-m2-fage-fen-vae-prec@etu.univ-lorraine.fr","fst-m2-fage-fge@etu.univ-lorraine.fr","fst-m2-fage-fge-fc@etu.univ-lorraine.fr","fst-m2-fage-fge-fc-prec@etu.univ-lorraine.fr","fst-m2-fage-fge-fi@etu.univ-lorraine.fr","fst-m2-fage-fge-fi-prec@etu.univ-lorraine.fr","fst-m2-fage-fge-prec@etu.univ-lorraine.fr","fst-m2-fage-fge-vae@etu.univ-lorraine.fr","fst-m2-fage-fge-vae-prec@etu.univ-lorraine.fr","fst-m2-gc-ger@etu.univ-lorraine.fr","fst-m2-gc-ger-fc@etu.univ-lorraine.fr","fst-m2-gc-ger-fc-prec@etu.univ-lorraine.fr","fst-m2-gc-ger-fi@etu.univ-lorraine.fr","fst-m2-gc-ger-fi-prec@etu.univ-lorraine.fr","fst-m2-gc-ger-prec@etu.univ-lorraine.fr","fst-m2-gc-ger-vae@etu.univ-lorraine.fr","fst-m2-gc-ger-vae-prec@etu.univ-lorraine.fr","fst-m2-gc-sme@etu.univ-lorraine.fr","fst-m2-gc-sme-fc@etu.univ-lorraine.fr","fst-m2-gc-sme-fc-prec@etu.univ-lorraine.fr","fst-m2-gc-sme-fi@etu.univ-lorraine.fr","fst-m2-gc-sme-fi-prec@etu.univ-lorraine.fr","fst-m2-gc-sme-prec@etu.univ-lorraine.fr","fst-m2-gc-sme-vae@etu.univ-lorraine.fr","fst-m2-gc-sme-vae-prec@etu.univ-lorraine.fr","fst-m2-gpre-gpir@etu.univ-lorraine.fr","fst-m2-gpre-gpir-fc@etu.univ-lorraine.fr","fst-m2-gpre-gpir-fc-prec@etu.univ-lorraine.fr","fst-m2-gpre-gpir-fi@etu.univ-lorraine.fr","fst-m2-gpre-gpir-fi-prec@etu.univ-lorraine.fr","fst-m2-gpre-gpir-prec@etu.univ-lorraine.fr","fst-m2-gpre-gpir-vae@etu.univ-lorraine.fr","fst-m2-gpre-gpir-vae-prec@etu.univ-lorraine.fr","fst-m2-gpre-rm@etu.univ-lorraine.fr","fst-m2-gpre-rm-fc@etu.univ-lorraine.fr","fst-m2-gpre-rm-fc-prec@etu.univ-lorraine.fr","fst-m2-gpre-rm-fi@etu.univ-lorraine.fr","fst-m2-gpre-rm-fi-prec@etu.univ-lorraine.fr","fst-m2-gpre-rm-prec@etu.univ-lorraine.fr","fst-m2-gpre-rm-vae@etu.univ-lorraine.fr","fst-m2-gpre-rm-vae-prec@etu.univ-lorraine.fr","fst-m2-gpre-see@etu.univ-lorraine.fr","fst-m2-gpre-see-fc@etu.univ-lorraine.fr","fst-m2-gpre-see-fc-prec@etu.univ-lorraine.fr","fst-m2-gpre-see-fi@etu.univ-lorraine.fr","fst-m2-gpre-see-fi-prec@etu.univ-lorraine.fr","fst-m2-gpre-see-prec@etu.univ-lorraine.fr","fst-m2-gpre-see-vae@etu.univ-lorraine.fr","fst-m2-gpre-see-vae-prec@etu.univ-lorraine.fr","fst-m2-gpre-tp@etu.univ-lorraine.fr","fst-m2-gpre-tp-fc@etu.univ-lorraine.fr","fst-m2-gpre-tp-fc-prec@etu.univ-lorraine.fr","fst-m2-gpre-tp-fi@etu.univ-lorraine.fr","fst-m2-gpre-tp-fi-prec@etu.univ-lorraine.fr","fst-m2-gpre-tp-prec@etu.univ-lorraine.fr","fst-m2-gpre-tp-vae@etu.univ-lorraine.fr","fst-m2-gpre-tp-vae-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-ee@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-fc@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-fc-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-fi@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-fi-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-vae@etu.univ-lorraine.fr","fst-m2-i2e2i-ee-vae-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-eem@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-fc@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-fc-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-fi@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-fi-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-prec@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-vae@etu.univ-lorraine.fr","fst-m2-i2e2i-eem-vae-prec@etu.univ-lorraine.fr","fst-m2-info-ipac@etu.univ-lorraine.fr","fst-m2-info-ipac-fc@etu.univ-lorraine.fr","fst-m2-info-ipac-fc-prec@etu.univ-lorraine.fr","fst-m2-info-ipac-fi@etu.univ-lorraine.fr","fst-m2-info-ipac-fi-prec@etu.univ-lorraine.fr","fst-m2-info-ipac-prec@etu.univ-lorraine.fr","fst-m2-info-ipac-vae@etu.univ-lorraine.fr","fst-m2-info-ipac-vae-prec@etu.univ-lorraine.fr","fst-m2-info-lmfi@etu.univ-lorraine.fr","fst-m2-info-lmfi-fc@etu.univ-lorraine.fr","fst-m2-info-lmfi-fc-prec@etu.univ-lorraine.fr","fst-m2-info-lmfi-fi@etu.univ-lorraine.fr","fst-m2-info-lmfi-fi-prec@etu.univ-lorraine.fr","fst-m2-info-lmfi-prec@etu.univ-lorraine.fr","fst-m2-info-lmfi-vae@etu.univ-lorraine.fr","fst-m2-info-lmfi-vae-prec@etu.univ-lorraine.fr","fst-m2-info-sssr@etu.univ-lorraine.fr","fst-m2-info-sssr-fc@etu.univ-lorraine.fr","fst-m2-info-sssr-fc-prec@etu.univ-lorraine.fr","fst-m2-info-sssr-fi@etu.univ-lorraine.fr","fst-m2-info-sssr-fi-prec@etu.univ-lorraine.fr","fst-m2-info-sssr-prec@etu.univ-lorraine.fr","fst-m2-info-sssr-vae@etu.univ-lorraine.fr","fst-m2-info-sssr-vae-prec@etu.univ-lorraine.fr","fst-m2-isc-isp@etu.univ-lorraine.fr","fst-m2-isc-isp-fc@etu.univ-lorraine.fr","fst-m2-isc-isp-fc-prec@etu.univ-lorraine.fr","fst-m2-isc-isp-fi@etu.univ-lorraine.fr","fst-m2-isc-isp-fi-prec@etu.univ-lorraine.fr","fst-m2-isc-isp-prec@etu.univ-lorraine.fr","fst-m2-isc-isp-vae@etu.univ-lorraine.fr","fst-m2-isc-isp-vae-prec@etu.univ-lorraine.fr","fst-m2-isc-mpc@etu.univ-lorraine.fr","fst-m2-isc-mpc-fc@etu.univ-lorraine.fr","fst-m2-isc-mpc-fc-prec@etu.univ-lorraine.fr","fst-m2-isc-mpc-fi@etu.univ-lorraine.fr","fst-m2-isc-mpc-fi-prec@etu.univ-lorraine.fr","fst-m2-isc-mpc-prec@etu.univ-lorraine.fr","fst-m2-isc-mpc-vae@etu.univ-lorraine.fr","fst-m2-isc-mpc-vae-prec@etu.univ-lorraine.fr","fst-m2-isc-s-tic@etu.univ-lorraine.fr","fst-m2-isc-s-tic-fc@etu.univ-lorraine.fr","fst-m2-isc-s-tic-fc-prec@etu.univ-lorraine.fr","fst-m2-isc-s-tic-fi@etu.univ-lorraine.fr","fst-m2-isc-s-tic-fi-prec@etu.univ-lorraine.fr","fst-m2-isc-s-tic-prec@etu.univ-lorraine.fr","fst-m2-isc-s-tic-vae@etu.univ-lorraine.fr","fst-m2-isc-s-tic-vae-prec@etu.univ-lorraine.fr","fst-m2-maths-imoi@etu.univ-lorraine.fr","fst-m2-maths-imoi-fc@etu.univ-lorraine.fr","fst-m2-maths-imoi-fc-prec@etu.univ-lorraine.fr","fst-m2-maths-imoi-fi@etu.univ-lorraine.fr","fst-m2-maths-imoi-fi-prec@etu.univ-lorraine.fr","fst-m2-maths-imoi-prec@etu.univ-lorraine.fr","fst-m2-maths-imoi-vae@etu.univ-lorraine.fr","fst-m2-maths-imoi-vae-prec@etu.univ-lorraine.fr","fst-m2-maths-mfa@etu.univ-lorraine.fr","fst-m2-maths-mfa-fc@etu.univ-lorraine.fr","fst-m2-maths-mfa-fc-prec@etu.univ-lorraine.fr","fst-m2-maths-mfa-fi@etu.univ-lorraine.fr","fst-m2-maths-mfa-fi-prec@etu.univ-lorraine.fr","fst-m2-maths-mfa-prec@etu.univ-lorraine.fr","fst-m2-maths-mfa-vae@etu.univ-lorraine.fr","fst-m2-maths-mfa-vae-prec@etu.univ-lorraine.fr","fst-m2-meep-ep@etu.univ-lorraine.fr","fst-m2-meep-ep-fc@etu.univ-lorraine.fr","fst-m2-meep-ep-fc-prec@etu.univ-lorraine.fr","fst-m2-meep-ep-fi@etu.univ-lorraine.fr","fst-m2-meep-ep-fi-prec@etu.univ-lorraine.fr","fst-m2-meep-ep-prec@etu.univ-lorraine.fr","fst-m2-meep-ep-vae@etu.univ-lorraine.fr","fst-m2-meep-ep-vae-prec@etu.univ-lorraine.fr","fst-m2-meep-me@etu.univ-lorraine.fr","fst-m2-meep-me-fc@etu.univ-lorraine.fr","fst-m2-meep-me-fc-prec@etu.univ-lorraine.fr","fst-m2-meep-me-fi@etu.univ-lorraine.fr","fst-m2-meep-me-fi-prec@etu.univ-lorraine.fr","fst-m2-meep-me-prec@etu.univ-lorraine.fr","fst-m2-meep-me-vae@etu.univ-lorraine.fr","fst-m2-meep-me-vae-prec@etu.univ-lorraine.fr","fst-m2-physique-mcn@etu.univ-lorraine.fr","fst-m2-physique-mcn-fc@etu.univ-lorraine.fr","fst-m2-physique-mcn-fc-prec@etu.univ-lorraine.fr","fst-m2-physique-mcn-fi@etu.univ-lorraine.fr","fst-m2-physique-mcn-fi-prec@etu.univ-lorraine.fr","fst-m2-physique-mcn-prec@etu.univ-lorraine.fr","fst-m2-physique-mcn-vae@etu.univ-lorraine.fr","fst-m2-physique-mcn-vae-prec@etu.univ-lorraine.fr","fst-m2-physique-sfp@etu.univ-lorraine.fr","fst-m2-physique-sfp-fc@etu.univ-lorraine.fr","fst-m2-physique-sfp-fc-prec@etu.univ-lorraine.fr","fst-m2-physique-sfp-fi@etu.univ-lorraine.fr","fst-m2-physique-sfp-fi-prec@etu.univ-lorraine.fr","fst-m2-physique-sfp-prec@etu.univ-lorraine.fr","fst-m2-physique-sfp-vae@etu.univ-lorraine.fr","fst-m2-physique-sfp-vae-prec@etu.univ-lorraine.fr","fst-m2-spim-met@etu.univ-lorraine.fr","fst-m2-spim-met-fc@etu.univ-lorraine.fr","fst-m2-spim-met-fc-prec@etu.univ-lorraine.fr","fst-m2-spim-met-fi@etu.univ-lorraine.fr","fst-m2-spim-met-fi-prec@etu.univ-lorraine.fr","fst-m2-spim-met-prec@etu.univ-lorraine.fr","fst-m2-spim-met-vae@etu.univ-lorraine.fr","fst-m2-spim-met-vae-prec@etu.univ-lorraine.fr","fst-m2-spim-pcm@etu.univ-lorraine.fr","fst-m2-spim-pcm-fc@etu.univ-lorraine.fr","fst-m2-spim-pcm-fc-prec@etu.univ-lorraine.fr","fst-m2-spim-pcm-fi@etu.univ-lorraine.fr","fst-m2-spim-pcm-fi-prec@etu.univ-lorraine.fr","fst-m2-spim-pcm-prec@etu.univ-lorraine.fr","fst-m2-spim-pcm-vae@etu.univ-lorraine.fr","fst-m2-spim-pcm-vae-prec@etu.univ-lorraine.fr","mim-m1-gc@etu.univ-lorraine.fr","mim-m1-gc-fc@etu.univ-lorraine.fr","mim-m1-gc-fc-prec@etu.univ-lorraine.fr","mim-m1-gc-fi@etu.univ-lorraine.fr","mim-m1-gc-fi-prec@etu.univ-lorraine.fr","mim-m1-gc-prec@etu.univ-lorraine.fr","mim-m1-inf-fc@etu.univ-lorraine.fr","mim-m1-inf-fc-prec@etu.univ-lorraine.fr","mim-m1-inf-fi@etu.univ-lorraine.fr","mim-m1-inf-fi-prec@etu.univ-lorraine.fr","mim-m1-mat@etu.univ-lorraine.fr","mim-m1-mat-fc@etu.univ-lorraine.fr","mim-m1-mat-fc-prec@etu.univ-lorraine.fr","mim-m1-mat-fi@etu.univ-lorraine.fr","mim-m1-mat-fi-prec@etu.univ-lorraine.fr","mim-m1-mat-prec@etu.univ-lorraine.fr","mim-m1-spi@etu.univ-lorraine.fr","mim-m1-spi-fc@etu.univ-lorraine.fr","mim-m1-spi-fc-prec@etu.univ-lorraine.fr","mim-m1-spi-fi@etu.univ-lorraine.fr","mim-m1-spi-fi-prec@etu.univ-lorraine.fr","mim-m1-spi-prec@etu.univ-lorraine.fr","mim-m2-gc-cem-fc@etu.univ-lorraine.fr","mim-m2-gc-cem-fc-prec@etu.univ-lorraine.fr","mim-m2-gc-cem-fi@etu.univ-lorraine.fr","mim-m2-gc-cem-fi-prec@etu.univ-lorraine.fr","mim-m2-gc-cem-vae@etu.univ-lorraine.fr","mim-m2-gc-cem-vae-prec@etu.univ-lorraine.fr","mim-m2-inf-gi@etu.univ-lorraine.fr","mim-m2-inf-gi-fc@etu.univ-lorraine.fr","mim-m2-inf-gi-fc-prec@etu.univ-lorraine.fr","mim-m2-inf-gi-fi@etu.univ-lorraine.fr","mim-m2-inf-gi-fi-prec@etu.univ-lorraine.fr","mim-m2-inf-gi-isfates@etu.univ-lorraine.fr","mim-m2-inf-gi-isfates-prec@etu.univ-lorraine.fr","mim-m2-inf-gi-prec@etu.univ-lorraine.fr","mim-m2-inf-id@etu.univ-lorraine.fr","mim-m2-inf-id-fc@etu.univ-lorraine.fr","mim-m2-inf-id-fc-prec@etu.univ-lorraine.fr","mim-m2-inf-id-fi@etu.univ-lorraine.fr","mim-m2-inf-id-fi-prec@etu.univ-lorraine.fr","mim-m2-inf-id-isfates@etu.univ-lorraine.fr","mim-m2-inf-id-isfates-prec@etu.univ-lorraine.fr","mim-m2-inf-id-prec@etu.univ-lorraine.fr","mim-m2-inf-id-vae@etu.univ-lorraine.fr","mim-m2-inf-id-vae-prec@etu.univ-lorraine.fr","mim-m2-inf-ipac@etu.univ-lorraine.fr","mim-m2-inf-ipac-fc@etu.univ-lorraine.fr","mim-m2-inf-ipac-fc-prec@etu.univ-lorraine.fr","mim-m2-inf-ipac-fi@etu.univ-lorraine.fr","mim-m2-inf-ipac-fi-prec@etu.univ-lorraine.fr","mim-m2-inf-ipac-isfates@etu.univ-lorraine.fr","mim-m2-inf-ipac-isfates-prec@etu.univ-lorraine.fr","mim-m2-inf-ipac-prec@etu.univ-lorraine.fr","mim-m2-inf-ipac-vae@etu.univ-lorraine.fr","mim-m2-inf-ipac-vae-prec@etu.univ-lorraine.fr","mim-m2-inf-sssr@etu.univ-lorraine.fr","mim-m2-inf-sssr-fc@etu.univ-lorraine.fr","mim-m2-inf-sssr-fc-prec@etu.univ-lorraine.fr","mim-m2-inf-sssr-fi@etu.univ-lorraine.fr","mim-m2-inf-sssr-fi-prec@etu.univ-lorraine.fr","mim-m2-inf-sssr-isfates@etu.univ-lorraine.fr","mim-m2-inf-sssr-isfates-prec@etu.univ-lorraine.fr","mim-m2-inf-sssr-prec@etu.univ-lorraine.fr","mim-m2-inf-sssr-vae@etu.univ-lorraine.fr","mim-m2-inf-sssr-vae-prec@etu.univ-lorraine.fr","mim-m2-mat-mfa@etu.univ-lorraine.fr","mim-m2-mat-mfa-fc@etu.univ-lorraine.fr","mim-m2-mat-mfa-fc-prec@etu.univ-lorraine.fr","mim-m2-mat-mfa-fi@etu.univ-lorraine.fr","mim-m2-mat-mfa-fi-prec@etu.univ-lorraine.fr","mim-m2-mat-mfa-prec@etu.univ-lorraine.fr","mim-m2-mat-mfa-vae@etu.univ-lorraine.fr","mim-m2-mat-mfa-vae-prec@etu.univ-lorraine.fr","mim-m2-mat-psa@etu.univ-lorraine.fr","mim-m2-mat-psa-fc@etu.univ-lorraine.fr","mim-m2-mat-psa-fc-prec@etu.univ-lorraine.fr","mim-m2-mat-psa-fi@etu.univ-lorraine.fr","mim-m2-mat-psa-fi-prec@etu.univ-lorraine.fr","mim-m2-mat-psa-prec@etu.univ-lorraine.fr","mim-m2-mat-psa-vae@etu.univ-lorraine.fr","mim-m2-mat-psa-vae-prec@etu.univ-lorraine.fr","mim-m2-spi-gsi-fc@etu.univ-lorraine.fr","mim-m2-spi-gsi-fc-prec@etu.univ-lorraine.fr","mim-m2-spi-gsi-fi@etu.univ-lorraine.fr","mim-m2-spi-gsi-fi-prec@etu.univ-lorraine.fr","mim-m2-spi-gsi-vae@etu.univ-lorraine.fr","mim-m2-spi-gsi-vae-prec@etu.univ-lorraine.fr","mim-m2-spi-imm@etu.univ-lorraine.fr","mim-m2-spi-imm-fc@etu.univ-lorraine.fr","mim-m2-spi-imm-fc-prec@etu.univ-lorraine.fr","mim-m2-spi-imm-fi@etu.univ-lorraine.fr","mim-m2-spi-imm-fi-prec@etu.univ-lorraine.fr","mim-m2-spi-imm-prec@etu.univ-lorraine.fr","mim-m2-spi-imm-vae@etu.univ-lorraine.fr","mim-m2-spi-imm-vae-prec@etu.univ-lorraine.fr","mim-m2-umgcv-um-cv@etu.univ-lorraine.fr","mim-m2-umgcv-um-cv-prec@etu.univ-lorraine.fr","scifa-m1-3e@etu.univ-lorraine.fr","scifa-m1-3e-prec@etu.univ-lorraine.fr","scifa-m1-a3r@etu.univ-lorraine.fr","scifa-m1-a3r-prec@etu.univ-lorraine.fr","scifa-m1-chim@etu.univ-lorraine.fr","scifa-m1-chim-prec@etu.univ-lorraine.fr","scifa-m1-i2e2i@etu.univ-lorraine.fr","scifa-m1-i2e2i-prec@etu.univ-lorraine.fr","scifa-m1-meef-eps@etu.univ-lorraine.fr","scifa-m1-meef-eps-prec@etu.univ-lorraine.fr","scifa-m1-meef-pc@etu.univ-lorraine.fr","scifa-m1-meef-pc-prec@etu.univ-lorraine.fr","scifa-m1-phys@etu.univ-lorraine.fr","scifa-m1-phys-prec@etu.univ-lorraine.fr","scifa-m1-staps@etu.univ-lorraine.fr","scifa-m1-staps-arhapa@etu.univ-lorraine.fr","scifa-m1-staps-arhapa-prec@etu.univ-lorraine.fr","scifa-m2-cores-prec@etu.univ-lorraine.fr","scifa-m2-e2sa@etu.univ-lorraine.fr","scifa-m2-e2sa-prec@etu.univ-lorraine.fr","scifa-m2-ge@etu.univ-lorraine.fr","scifa-m2-ge-prec@etu.univ-lorraine.fr","scifa-m2-gemarec@etu.univ-lorraine.fr","scifa-m2-gemarec-prec@etu.univ-lorraine.fr","scifa-m2-ishm@etu.univ-lorraine.fr","scifa-m2-ishm-prec@etu.univ-lorraine.fr","scifa-m2-mda-prec@etu.univ-lorraine.fr","scifa-m2-meef-eps-pea@etu.univ-lorraine.fr","scifa-m2-meef-eps-pea-prec@etu.univ-lorraine.fr","scifa-m2-meef-eps-pepa@etu.univ-lorraine.fr","scifa-m2-meef-eps-pepa-prec@etu.univ-lorraine.fr","scifa-m2-meef-mpc@etu.univ-lorraine.fr","scifa-m2-meef-pc-pea@etu.univ-lorraine.fr","scifa-m2-meef-pc-pea-prec@etu.univ-lorraine.fr","scifa-m2-meef-pc-pepa@etu.univ-lorraine.fr","scifa-m2-meef-pc-pepa-prec@etu.univ-lorraine.fr","scifa-m2-meen@etu.univ-lorraine.fr","scifa-m2-meen-ap@etu.univ-lorraine.fr","scifa-m2-meen-prec@etu.univ-lorraine.fr","scifa-m2-mti@etu.univ-lorraine.fr","scifa-m2-mti-prec@etu.univ-lorraine.fr","scifa-m2-pom@etu.univ-lorraine.fr","scifa-m2-pom-prec@etu.univ-lorraine.fr","scifa-m2-rsef@etu.univ-lorraine.fr","scifa-m2-rsef-prec@etu.univ-lorraine.fr","scifa-m2-sce@etu.univ-lorraine.fr","scifa-m2-sce-prec@etu.univ-lorraine.fr","scifa-m2-staps-arhapa@etu.univ-lorraine.fr","scifa-m2-staps-arhapa-prec@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-fc@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-fc-prec@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-fi@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-fi-prec@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-luxembourg@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-luxembourg-prec@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-maroc@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-maroc-prec@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-slovaquie@etu.univ-lorraine.fr","ceu-m-1-ncy-umeur-slovaquie-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-ct-fc@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-ct-fc-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-ct-fi@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-ct-fi-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-ct-vae@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-ct-vae-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-bulgarie@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-bulgarie-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-fc@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-fc-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-fi@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-fi-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-luxembourg@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-luxembourg-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-serbie@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-serbie-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-vae@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-eu-vae-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-rp-fc@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-rp-fc-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-rp-fi@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-rp-fi-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-rp-vae@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-rp-vae-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-fc@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-fc-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-fi@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-fi-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-maroc@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-maroc-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-slovaquie@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-slovaquie-prec@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-vae@etu.univ-lorraine.fr","ceu-m-2-ncy-umeur-um-up-vae-prec@etu.univ-lorraine.fr","ceu-m1-ncy@etu.univ-lorraine.fr","ceu-m1-ncy-prec@etu.univ-lorraine.fr","ceu-m2-ncy@etu.univ-lorraine.fr","ceu-m2-ncy-prec@etu.univ-lorraine.fr","dea-l3-droitsgs@etu.univ-lorraine.fr","dea-l3-droitsgs-prec@etu.univ-lorraine.fr","dea-m1-droit-entreprise@etu.univ-lorraine.fr","dea-m1-droit-entreprise-prec@etu.univ-lorraine.fr","dea-m1-droitprive@etu.univ-lorraine.fr","dea-m1-droitprive-prec@etu.univ-lorraine.fr","dea-m1-droitpublic@etu.univ-lorraine.fr","dea-m1-droitpublic-prec@etu.univ-lorraine.fr","dea-m1-ecoappli@etu.univ-lorraine.fr","dea-m1-ecoappli-prec@etu.univ-lorraine.fr","dea-m1-efm@etu.univ-lorraine.fr","dea-m1-efm-prec@etu.univ-lorraine.fr","dea-m1-umcrm-fc@etu.univ-lorraine.fr","dea-m1-umcrm-fc-prec@etu.univ-lorraine.fr","dea-m1-umcrm-fi@etu.univ-lorraine.fr","dea-m1-umcrm-fi-prec@etu.univ-lorraine.fr","dea-m1-umdep-fc@etu.univ-lorraine.fr","dea-m1-umdep-fc-prec@etu.univ-lorraine.fr","dea-m1-umdep-fi@etu.univ-lorraine.fr","dea-m1-umdep-fi-prec@etu.univ-lorraine.fr","dea-m1-umeap-fc@etu.univ-lorraine.fr","dea-m1-umeap-fc-prec@etu.univ-lorraine.fr","dea-m1-umeap-fi@etu.univ-lorraine.fr","dea-m1-umeap-fi-prec@etu.univ-lorraine.fr","dea-m1-umefm-fc@etu.univ-lorraine.fr","dea-m1-umefm-fc-prec@etu.univ-lorraine.fr","dea-m1-umefm-fi@etu.univ-lorraine.fr","dea-m1-umefm-fi-prec@etu.univ-lorraine.fr","dea-m1-umpol-fc@etu.univ-lorraine.fr","dea-m1-umpol-fc-prec@etu.univ-lorraine.fr","dea-m1-umpol-fi@etu.univ-lorraine.fr","dea-m1-umpol-fi-prec@etu.univ-lorraine.fr","dea-m2-2e2s@etu.univ-lorraine.fr","dea-m2-2e2s-prec@etu.univ-lorraine.fr","dea-m2-ceco@etu.univ-lorraine.fr","dea-m2-ceco-prec@etu.univ-lorraine.fr","dea-m2-collterr@etu.univ-lorraine.fr","dea-m2-collterr-prec@etu.univ-lorraine.fr","dea-m2-dat@etu.univ-lorraine.fr","dea-m2-dat-prec@etu.univ-lorraine.fr","dea-m2-drtprotrans@etu.univ-lorraine.fr","dea-m2-drtprotrans-prec@etu.univ-lorraine.fr","dea-m2-duc@etu.univ-lorraine.fr","dea-m2-duc-prec@etu.univ-lorraine.fr","dea-m2-ecodevloc@etu.univ-lorraine.fr","dea-m2-ecodevloc-prec@etu.univ-lorraine.fr","dea-m2-expstats@etu.univ-lorraine.fr","dea-m2-expstats-prec@etu.univ-lorraine.fr","dea-m2-fiscalappl@etu.univ-lorraine.fr","dea-m2-fiscalappl-prec@etu.univ-lorraine.fr","dea-m2-umcrm-fc@etu.univ-lorraine.fr","dea-m2-umcrm-fc-prec@etu.univ-lorraine.fr","dea-m2-umcrm-fi@etu.univ-lorraine.fr","dea-m2-umcrm-fi-prec@etu.univ-lorraine.fr","dea-m2-umdep-fc@etu.univ-lorraine.fr","dea-m2-umdep-fc-prec@etu.univ-lorraine.fr","dea-m2-umdep-fi@etu.univ-lorraine.fr","dea-m2-umdep-fi-prec@etu.univ-lorraine.fr","dea-m2-umeap-fc@etu.univ-lorraine.fr","dea-m2-umeap-fc-prec@etu.univ-lorraine.fr","dea-m2-umeap-fi@etu.univ-lorraine.fr","dea-m2-umeap-fi-prec@etu.univ-lorraine.fr","dea-m2-umefm-fc@etu.univ-lorraine.fr","dea-m2-umefm-fc-prec@etu.univ-lorraine.fr","dea-m2-umefm-fi@etu.univ-lorraine.fr","dea-m2-umefm-fi-prec@etu.univ-lorraine.fr","dea-m2-umpol-fc@etu.univ-lorraine.fr","dea-m2-umpol-fc-prec@etu.univ-lorraine.fr","dea-m2-umpol-fi@etu.univ-lorraine.fr","dea-m2-umpol-fi-prec@etu.univ-lorraine.fr","dseg-finance-francfort@etu.univ-lorraine.fr","dseg-m-2-ncy@etu.univ-lorraine.fr","dseg-m-2-ncy-prec@etu.univ-lorraine.fr","dseg-m-2-ncy-umdep-um-ji-cp@etu.univ-lorraine.fr","dseg-m-2-ncy-umdep-um-ji-cp-prec@etu.univ-lorraine.fr","dseg-m-2-ncy-umeap-um-xe-fc@etu.univ-lorraine.fr","dseg-m-2-ncy-umeap-um-xe-fc-prec@etu.univ-lorraine.fr","dseg-m-2-ncy-umeap-um-xe-fi@etu.univ-lorraine.fr","dseg-m-2-ncy-umeap-um-xe-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umcrm-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umcrm-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umcrm-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umcrm-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umdep-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umdep-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umdep-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umdep-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umeap-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umeap-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umeap-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umeap-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umefm-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umefm-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umefm-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umefm-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umfcc-ap@etu.univ-lorraine.fr","dseg-m1-ncy-umfcc-ap-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umfcc-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umfcc-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umfcc-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umfcc-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umme2-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umme2-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umme2-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umme2-fi-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umpol-fc@etu.univ-lorraine.fr","dseg-m1-ncy-umpol-fc-prec@etu.univ-lorraine.fr","dseg-m1-ncy-umpol-fi@etu.univ-lorraine.fr","dseg-m1-ncy-umpol-fi-prec@etu.univ-lorraine.fr","dseg-m2-epi-umpol-um-cp-fc@etu.univ-lorraine.fr","dseg-m2-epi-umpol-um-cp-fc-prec@etu.univ-lorraine.fr","dseg-m2-epi-umpol-um-cp-fi@etu.univ-lorraine.fr","dseg-m2-epi-umpol-um-cp-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-cx-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-cx-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-cx-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-cx-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dc-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dc-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dc-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dc-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dp-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dp-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dp-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-dp-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-hd-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-hd-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-hd-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-hd-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-sc-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-sc-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-sc-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umcrm-um-sc-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-df-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-df-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-df-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-df-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-dr-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-dr-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-dr-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-dr-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-ji-cvtuce@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-ji-cvtuce-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-ji-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-ji-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-ji-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-ji-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-tp-al@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-tp-al-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-tp-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-tp-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-tp-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umdep-um-tp-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-av-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-av-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-av-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-av-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-mf-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-mf-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-mf-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umefm-um-mf-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-bq-ap@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-bq-ap-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-bq-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-bq-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-bq-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-bq-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-if-ap@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-if-ap-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-if-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-im-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-im-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-im-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-im-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-ng-ap@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-ng-ap-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-ng-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-ng-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-ng-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umfcc-um-ng-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-cp-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-cp-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-cp-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-cp-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-di-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-di-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-di-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-di-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-gv-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-gv-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-gv-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-gv-fi-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-mp-fc@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-mp-fc-prec@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-mp-fi@etu.univ-lorraine.fr","dseg-m2-ncy-umpol-um-mp-fi-prec@etu.univ-lorraine.fr","esm-iae-m1-aa@etu.univ-lorraine.fr","esm-iae-m1-aa-prec@etu.univ-lorraine.fr","esm-iae-m1-agf@etu.univ-lorraine.fr","esm-iae-m1-agf-prec@etu.univ-lorraine.fr","esm-iae-m1-mi@etu.univ-lorraine.fr","esm-iae-m1-mi-prec@etu.univ-lorraine.fr","esm-iae-m1-moss@etu.univ-lorraine.fr","esm-iae-m1-moss-prec@etu.univ-lorraine.fr","esm-iae-m1-mv@etu.univ-lorraine.fr","esm-iae-m1-mv-prec@etu.univ-lorraine.fr","esm-iae-m1-rh@etu.univ-lorraine.fr","esm-iae-m1-rh-prec@etu.univ-lorraine.fr","esm-iae-m2-ae1@etu.univ-lorraine.fr","esm-iae-m2-ae1-prec@etu.univ-lorraine.fr","esm-iae-m2-ae1an@etu.univ-lorraine.fr","esm-iae-m2-ae1an-prec@etu.univ-lorraine.fr","esm-iae-m2-ae2@etu.univ-lorraine.fr","esm-iae-m2-ae2-prec@etu.univ-lorraine.fr","esm-iae-m2-cga@etu.univ-lorraine.fr","esm-iae-m2-cga-prec@etu.univ-lorraine.fr","esm-iae-m2-eda@etu.univ-lorraine.fr","esm-iae-m2-eda-prec@etu.univ-lorraine.fr","esm-iae-m2-fi@etu.univ-lorraine.fr","esm-iae-m2-fi-prec@etu.univ-lorraine.fr","esm-iae-m2-fi-tudor@etu.univ-lorraine.fr","esm-iae-m2-fi-tudor-prec@etu.univ-lorraine.fr","esm-iae-m2-mcl@etu.univ-lorraine.fr","esm-iae-m2-mcl-prec@etu.univ-lorraine.fr","esm-iae-m2-mdp@etu.univ-lorraine.fr","esm-iae-m2-mdp-prec@etu.univ-lorraine.fr","esm-iae-m2-mdpi@etu.univ-lorraine.fr","esm-iae-m2-mdpi-paris@etu.univ-lorraine.fr","esm-iae-m2-mdpi-paris-prec@etu.univ-lorraine.fr","esm-iae-m2-mdpi-prec@etu.univ-lorraine.fr","esm-iae-m2-mdsh@etu.univ-lorraine.fr","esm-iae-m2-mdsh-prec@etu.univ-lorraine.fr","esm-iae-m2-mess@etu.univ-lorraine.fr","esm-iae-m2-mess-prec@etu.univ-lorraine.fr","esm-iae-m2-mfa@etu.univ-lorraine.fr","esm-iae-m2-mfa-prec@etu.univ-lorraine.fr","esm-iae-m2-mh@etu.univ-lorraine.fr","esm-iae-m2-mh-prec@etu.univ-lorraine.fr","esm-iae-m2-mq@etu.univ-lorraine.fr","esm-iae-m2-mq-prec@etu.univ-lorraine.fr","esm-iae-m2-mq-tudor@etu.univ-lorraine.fr","esm-iae-m2-mq-tudor-prec@etu.univ-lorraine.fr","esm-iae-m2-mrho@etu.univ-lorraine.fr","esm-iae-m2-mrho-prec@etu.univ-lorraine.fr","ipag-m1-ncy-ummap@etu.univ-lorraine.fr","ipag-m1-ncy-ummap-fc@etu.univ-lorraine.fr","ipag-m1-ncy-ummap-fc-prec@etu.univ-lorraine.fr","ipag-m1-ncy-ummap-fi@etu.univ-lorraine.fr","ipag-m1-ncy-ummap-fi-prec@etu.univ-lorraine.fr","ipag-m1-ncy-ummap-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-umfcc@etu.univ-lorraine.fr","isam-iae-m1-ncy-umfcc-fc-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-umfcc-fi-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummap-fc-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummap-fi@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummap-fi-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummkv@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummkv-cp-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummkv-fc-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummkv-fi-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummos@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummos-ead@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummos-ead-fc-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummos-ead-fi-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummos-fc-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-ummos-fi-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-umrhm@etu.univ-lorraine.fr","isam-iae-m1-ncy-umrhm-fc-prec@etu.univ-lorraine.fr","isam-iae-m1-ncy-umrhm-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umaaf-um-ae-cp@etu.univ-lorraine.fr","isam-iae-m2-ncy-umaaf-um-ae-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umaaf-um-ae-fc@etu.univ-lorraine.fr","isam-iae-m2-ncy-umaaf-um-ae-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umaaf-um-ae-fi@etu.univ-lorraine.fr","isam-iae-m2-ncy-umaaf-um-ae-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-af@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-af-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-af-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-cc@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-cc-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-cc-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-cc-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-if@etu.univ-lorraine.fr","isam-iae-m2-ncy-umfcc-um-if-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umlea-um-ei@etu.univ-lorraine.fr","isam-iae-m2-ncy-umlea-um-ei-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umlea-um-ei-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummap-um-gs@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummap-um-gs-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummap-um-gs-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummap-um-op@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummap-um-op-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummap-um-op-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummkv-um-kg@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummkv-um-kg-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummkv-um-kg-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummkv-um-kg-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-ia@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-ia-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-ia-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-ia-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-mh@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-mh-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-mh-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-sn@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-sn-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-sn-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-ummos-um-sn-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-ha@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-ha-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-ha-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-ha-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-hv@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-hv-cp-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-hv-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ncy-umrhm-um-hv-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-umaaf-um-ae-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-umaaf-um-ae-fi-prec@etu.univ-lorraine.fr","isam-iae-m2-ummap-um-op@etu.univ-lorraine.fr","isam-iae-m2-ummap-um-op-fc-prec@etu.univ-lorraine.fr","isam-iae-m2-ummap-um-op-fi-prec@etu.univ-lorraine.fr","mathinfo-m1-miage@etu.univ-lorraine.fr","mathinfo-m1-miage-cvthce@etu.univ-lorraine.fr","mathinfo-m1-ncy-miage-cvthce-prec@etu.univ-lorraine.fr","mathinfo-m1-ncy-miage-fc-prec@etu.univ-lorraine.fr","mathinfo-m1-ncy-miage-fi-prec@etu.univ-lorraine.fr","mathinfo-m1-ncy-sca-fc-prec@etu.univ-lorraine.fr","mathinfo-m1-ncy-sca-fi-prec@etu.univ-lorraine.fr","mathinfo-m1-sca@etu.univ-lorraine.fr","mathinfo-m2-miage-acsi@etu.univ-lorraine.fr","mathinfo-m2-miage-acsi-cvthce@etu.univ-lorraine.fr","mathinfo-m2-miage-acsi-vae@etu.univ-lorraine.fr","mathinfo-m2-miage-ii@etu.univ-lorraine.fr","mathinfo-m2-miage-ii-vae@etu.univ-lorraine.fr","mathinfo-m2-miage-sid@etu.univ-lorraine.fr","mathinfo-m2-miage-sid-cvthce@etu.univ-lorraine.fr","mathinfo-m2-miage-sid-vae@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-acsi-cvthce-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-acsi-fc-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-acsi-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-acsi-vae-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-ii-fc-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-ii-fi-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-ii-vae-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-sid-cvthce-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-sid-fc-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-sid-fi-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-miage-sid-vae-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-sca-scmn-fc-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-sca-scmn-fi-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-sca-scmn-vae-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-sca-tal-fc-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-sca-tal-fi-prec@etu.univ-lorraine.fr","mathinfo-m2-ncy-sca-tal-vae-prec@etu.univ-lorraine.fr","mathinfo-m2-sca-scmn@etu.univ-lorraine.fr","mathinfo-m2-sca-scmn-vae@etu.univ-lorraine.fr","mathinfo-m2-sca-tal@etu.univ-lorraine.fr","mathinfo-m2-sca-tal-vae@etu.univ-lorraine.fr","alexandre.david@outlook.fr");
/*
	$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = utf8_encode('En recherche de stage ou d\'emploi ? Rendez-vous le  jeudi 22 octobre à Nancy pour le Forum Est-Horizon');
						$mail->Body = utf8_encode('Vous êtes <strong>étudiant en Master</strong> et à la recherche d’un <strong>stage</strong> ou d’un premier <strong>emploi</strong> ?</br></br>
Le Forum Est-Horizon vous donne rendez-vous  le :</br></br>
<center><strong>Jeudi 22 octobre 2015</strong></center>  </br>
     <center><strong>Nancy Centre Prouvé</strong></center>  </br>
     <center><strong>Entrée gratuite</strong></center>  </br></br>
<center><strong>Inscrivez-vous sur notre site internet : http://www.est-horizon.com </strong></center></br></br>

 Les secteurs représentés : <i>Banque, Conseil et Audit, Informatique, Industrie, Energie, BTP, Entrepreneuriat, Grandes Ecoles</i>.</br></br>
Cette année, notre salon s’associe avec Géologia, le premier salon national dédié aux <i>géosciences</i>,</br> pour un nombre total de 70 entreprises.</br></br>
En parallèle du salon : <i>concours d’entrepreneuriat, correction de CV, conférences.</i> 

						');
						$mail->IsHTML(true);
	for($i=0;$i<891;$i++){
	$mail->AddAddress($dest[$i]); //ClearAddresses()
	
	$mail->Send();
	$mail->ClearAddresses();}
/*$dest =array("alexandre.david@outlook.fr","alexandre.david3@etu.univ-lorraine.fr");
	
		$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = utf8_encode('Forum Est-Horizon : satisfaction visiteur');
						$mail->Body = utf8_encode('Vous avez participé à l’édition 2015 du Forum Est-Horizon, et nous vous en remercions.</br>
Nous vous serions très reconnaissants de bien vouloir prendre quelques instants pour remplir un</br>
 questionnaire de satisfaction en suivant le lien suivant : http://goo.gl/forms/wMt4qkQtyV </br></br>

En vous remerciant d’avance,</br>
L’équipe du Forum Est-Horizon


						');
						$mail->IsHTML(true);
						$mail->AddAddress("alexandre.david@outlook.fr"); //ClearAddresses()
	
	$mail->Send();
	/*for($i=0;$i<1;$i++){
	$mail->AddAddress($dest[$i]); //ClearAddresses()
	
	$mail->Send();
	$mail->ClearAddresses();}*/

	
	


?>