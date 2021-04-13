<?php
	header('Content-Type: text/html; charset=UTF-8');

	$database = array(
		'server' => 'mysql51-34.perso',
		'port'   => '',
		'user'   => 'esthorizbdd',
		'pw'     => 'M2i0n1e7s',
		'dbname' => 'esthorizbdd');
		
	$mail = secure_data($_POST['mail']);
	//echo gettype($mail);
	//echo $mail;
	$adresseIP = $_SERVER['REMOTE_ADDR'];
	
		
	//////CODER LES ELEMENTS PERMETTANT D'ASSURER L'INTEGRITE DES DONNES ET DU SITE (VERIFICATION)
	if(isset($mail)){
		if(!empty($mail)){
			if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				
				try{
					//COnnexion à la base de donnee
						$bdd = new PDO("mysql:host=mysql51-34.perso;dbname=esthorizbdd",$database['user'],$database['pw']);
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
						
					//////On vérifie si l'adresse mail est déjà dans la base de donnée et inscrit
						$checkSql = $bdd->prepare("SELECT COUNT(email) FROM newsletter WHERE email='$mail' AND mailingactif='1'");
						$checkSql->execute();
						$presence = $checkSql->fetch(PDO::FETCH_ASSOC);
						//print_r($presence);
						//echo $presence['COUNT(email)'];
						
						if ($presence['COUNT(email)'] >= 1){
					///	Si oui - Désinscription
							$date = date('Y-m-d h:i:s');
					       	
							$sql = $bdd->prepare("
								UPDATE newsletter
								SET mailingactif=FALSE
								WHERE email='$mail'");
							$sql->execute();  
							echo "Votre demande de désinscription pour : ".$mail." a bien été prise en compte";      
						}
						
						else {
					///	Si non - Inscription MAIS 2 cas : ancien inscrit ou nouvel arrivant
						$checknew = $bdd->prepare("SELECT COUNT(email) FROM newsletter WHERE email='$mail' AND mailingactif='0'");
						$checknew->execute();
						$new = $checknew->fetch(PDO::FETCH_ASSOC);
							if ($new['COUNT(email)'] >= 1){
								$sql = $bdd->prepare("
									UPDATE newsletter
									SET mailingactif=TRUE
									WHERE email='$mail'");
								$sql->execute();          			
								
							}
							else{
								$mailing = TRUE;
								$date = date('Y-m-d h:i:s');
								
								$sql = $bdd->prepare("
									INSERT INTO newsletter(email,ip,dates,mailingactif)
									VALUES (:mail, :ip, :date, :mailingactif)");
								$sql->bindParam(':mail',$mail);
								$sql->bindParam(':ip',$adresseIP);
								$sql->bindParam(':mailingactif',$mailing);
								$sql->bindParam(':date',$date);
								$sql->execute();			
							
							}

						echo "Votre demande d'inscription pour : ".$mail." a bien été prise en compte";
						}
				//		header("Location:/apps/gestionnewsletter/front/templates/ConfirmationReussite.html");
						
					}
				    	catch(PDOException $e){
						echo 'Impossible de traiter les données. Contacter l\'administrateur Erreur : '.$e->getMessage();
					}	
					$bdd = null;				
	
			}else{
				echo "Vous devez indiquer une adresse mail valide";
			}
		}else{
			echo "Vous devez remplir les champs vides";
		}
	
	
	
	}
	

	
	
	
	
	
	
	
	/*
		
	try{
	//COnnexion à la base de donnee
        	$bdd = new PDO("mysql:host=mysql51-34.perso;dbname=esthorizbdd",$database['user'],$database['pw']);
        	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        	
        //////On vérifie si l'adresse mail est déjà dans la base de donnée et inscrit
        	$checkSql = $bdd->prepare("SELECT COUNT(email) FROM newsletter WHERE email='$mail' AND mailingactif='1'");
        	$checkSql->execute();
        	$presence = $checkSql->fetch(PDO::FETCH_ASSOC);
        	//print_r($presence);
        	//echo $presence['COUNT(email)'];
        	
        	if ($presence['COUNT(email)'] >= 1){
        ///	Si oui - Désinscription
			$date = date('Y-m-d h:i:s');
	       	
			$sql = $bdd->prepare("
				UPDATE newsletter
				SET mailingactif=FALSE
				WHERE email='$mail'");
			$sql->execute();  
			echo "Votre demande de désinscription a bien été prise en compte";      
		}
		
        	else {
        ///	Si non - Inscription MAIS 2 cas : ancien inscrit ou nouvel arrivant
        	$checknew = $bdd->prepare("SELECT COUNT(email) FROM newsletter WHERE email='$mail' AND mailingactif='0'");
        	$checknew->execute();
        	$new = $checknew->fetch(PDO::FETCH_ASSOC);
        		if ($new['COUNT(email)'] >= 1){
				$sql = $bdd->prepare("
					UPDATE newsletter
					SET mailingactif=TRUE
					WHERE email='$mail'");
				$sql->execute();          			
        			
        		}
			else{
				$mailing = TRUE;
				$date = date('Y-m-d h:i:s');
				
				$sql = $bdd->prepare("
					INSERT INTO newsletter(email,ip,dates,mailingactif)
					VALUES (:mail, :ip, :date, :mailingactif)");
				$sql->bindParam(':mail',$mail);
				$sql->bindParam(':ip',$adresseIP);
				$sql->bindParam(':mailingactif',$mailing);
				$sql->bindParam(':date',$date);
				$sql->execute();			
			
			}

		echo "Votre demande d'inscription a bien été prise en compte";
		}
//		header("Location:/apps/gestionnewsletter/front/templates/ConfirmationReussite.html");
		
	}
    	catch(PDOException $e){
        	echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
        }	
        $bdd = null;
        
        */
        function secure_data($data){
        	$data = trim($data);
        	$data = stripslashes($data);
        	$data = htmlspecialchars($data);
        	return $data;
        }

?>
