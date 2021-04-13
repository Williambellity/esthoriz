<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif <Johan Dufau>
 * @version	$Id: apps/contact/front/main.php 0001 19-05-2011 Julien1619 $
 */

class equipeController extends WController {
	/*
	 * Chargement du modèle et de la view
	 */
	public function __construct() {
		include 'model.php';
		$this->model = new ContacterModel();
		
		include 'view.php';
		$this->setView(new ContacterView($this->model));
	}
	
	public function launch() {
		// Les notes
        
        
		WNote::treatNoteSession();
		
		$action = $this->getAskedAction();
		$this->forward($action, 'contacter');
	}
	
    public function contacter(){
        if(isset($_POST['issent'])){
            $sender_name = stripslashes($_POST["name"]);
            $sender_email = stripslashes($_POST["mail"]);
            $sender_message = stripslashes($_POST["bod"]);
            $sender_object = stripslashes($_POST["obj"]);
            $sender_organization = stripslashes($_POST["comp"]);
            $response = $_POST["g-recaptcha-response"];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = array(
                'secret' => '6LcMgmEUAAAAAM-pwruokHHCnD8UAtSAN46jLTxx',
                'response' => $_POST["g-recaptcha-response"]
            );
            $options = array(
                'http' => array (
                    'header' => "Content-Type: application/x-www-form-urlencoded",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                )
            );
            $context  = stream_context_create($options);
            $verify = file_get_contents($url, false, $context);
            $captcha_success=json_decode($verify);
            if ($captcha_success->success==true){
                if(
               $sender_name != "" and
               $sender_message != "" and
               $sender_object != "" and
               $sender_organization != "" and
               filter_var($sender_email, FILTER_VALIDATE_EMAIL)
               ) {
                
                    if(isset($_SESSION['userid'])) {
                        $uid = $_SESSION['userid'];
                    } else {
                        $uid = '';
                    }
                  
                    $mailData = array(
						'userid' => $uid,
						'name' => $sender_name,
						'organisme' => $sender_organization,
						'email' => $sender_email,
						'objet' => $sender_object,
						'message' => $sender_message,
					);
                    if (!$this->model->createMail($mailData)) {
						WNote::error("Erreur création mail", "Votre mail n'a pas été envoyé.<br />Veuillez reessayer", 'display');
					} else {
						WNote::success("Mail envoyé", "Votre mail a été envoyé avec succès.<br /><br />
							Vous venez de recevoir une copie à l'adresse email que vous avez indiquée.", 'display');
                        include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
						
						//Envoi du mail au forum
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = $sender_email;
						$mail->FromName = $sender_name.' de '.$sender_organization;
						$mail->Subject = $sender_object;
						$mail->Body = "message envoyé à partir de https://www.est-horizon.com/equipe<br>----------------------------------------------------<br>".nl2br($sender_message);
						$mail->IsHTML(true);
						$mail->AddAddress('forum@est-horizon.com');
						$mail->Send();
						
						unset($mail);
                        
                        
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = $sender_email;
						$mail->FromName = $sender_name.' de '.$sender_organization;
						$mail->Subject = $sender_object;
						$mail->Body = "message envoyé à partir de https://www.est-horizon.com/equipe<br>----------------------------------------------------<br>".nl2br($sender_message);
						$mail->IsHTML(true);
						$mail->AddAddress('informatique@est-horizon.com');
						$mail->Send();
						
						unset($mail);
						
						//Envoi de la copie à l'expéditeur
						
						$mail = new PHPMailer();
						$mail->CharSet = 'utf-8';
						$mail->From = 'forum@est-horizon.com';
						$mail->FromName = 'Forum Est-Horizon';
						$mail->Subject = 'Votre demande de contact pour le Forum Est-Horizon';
						$mail->Body = "<h1>Votre demande de contact pour le Forum Est-Horizon</h1>
						<p>Veuillez trouver ci-dessous la copie de votre demande de contacter</p>
						<div style=\"background:#f2f39c;margin-left:20px;\">
						<h2>Expéditeur : ".$sender_name." de ".$sender_organization."</h2>
						<h2>Objet : ".$sender_object."</h2>
						".nl2br($sender_message)."<br/></div>
						<h2>Le Forum Est-Horizon</h2>";
						$mail->IsHTML(true);
						$mail->AddAddress($sender_email);
						$mail->Send();
                    }
                }
                else{
                    WNote::error("Erreur", "veuillez bien remplir tous les champs", 'display');
                }
            }
            if ($captcha_success->success==false) {
                WNote::error("Erreur", "captcha invalide", 'display');
            }
        }
        else{
        $this->view->contacter();
		$this->render('contacter');
        }
    }
}

?>