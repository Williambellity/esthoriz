<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class BrochureController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new BrochureModel();
		
		include 'view.php';
		$this->setView(new BrochureView($this->model));
	}
	
	/**
	 * Récupération de l'id fourni en Url
	 * @param void
	 * @return int
	 */
	private function getId($argN = 0) {
		$args = WRoute::getArgs();
		if (empty($args[0])) {
			return 0;
		} else {
			return intval($args[$argN]);
		}
	}
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'brochure');
	}
	
	public function brochure() {
		$firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId();
		if (empty($_SESSION['firmid']) && ($firmid == 0 || !isset($_SESSION['access']['brochure']))  && (!isset($_SESSION['userid']) || $_SESSION['userid']!=1169)) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}
		
		// Les notes
		WNote::treatNoteSession();
		
		$data = WRequest::get(array( 'name', 'cat', 'website', 'creation_date', 'f1', 'f2', 'f3', 'f4','f5', 'pdg', 
		        'ca', 'adress','city','postal_code','country', 'co', 'implantation', 
			'effectifs', 
			'marques', 'contact', 'tel',
			'presentation', 'savoir', 'etu'), null, 'POST', false); //'adress', 'city', 'postal_code', 'country'
		//var_dump($data);
		if (!in_array(null, $data, true)) {
			$erreurs = array();
			
			// En cas d'erreur
			if (!empty($erreurs)) {
				WNote::error("Informations invalides", implode("<br />\n", $erreurs), 'display');
			} else {
				if(WRequest::get('maudit_ie6')) {
					$this->uploadLogo($firmid);
				}
				if ($this->model->writeBrochure($firmid, $data)) {
					// =====> ENVOI D'UN EMAIL AU POLE PAO (2011 = BASTIEN FAURE) <=====
					// include LIBS_DIR.'phpmailer'.DS.'class.phpmailer.php';
					// $mail = new PHPMailer();
					// $mail->CharSet = 'utf-8';
					// $mail->From = 'forum@mines.inpl-nancy.fr';
					// $mail->FromName = 'Forum Est-Horizon';
					// $mail->Subject = 'Nouvelle fiche signalétique complétée !';
					// $mail->Body = "Salut Bastien,<br /><br />
// L'entreprise <strong>".$data['name']."</strong> vient de compléter sa fiche.<br />
// Tu peux y accéder à cette adresse :
// <a href=\"http://www.est-horizon.com/brochure/".$firmid."\">http://www.est-horizon.com/brochure/".$firmid."</a><br /><br />

// Bises,<br />
// Johan";
					// $mail->IsHTML(true);
					// $mail->AddAddress('bastien.faure@mines.inpl-nancy.fr');
					// $mail->Send();
					// unset($mail);
					// FIN ENVOI MAIL
					
					WNote::success("Informations enregistrées", "Les informations concernant votre entreprise ont été enregistrées avec succès.
					<br/><br/>Celles-ci nous serviront à la confection de la brochure visiteurs.", 'session');
					header('location: '.WRoute::getDir().'brochure/'.(empty($_SESSION['firmid']) ? $firmid : ''));
				} else {
					WNote::error("Erreur", "Une erreur s'est produite.", 'display');
				}
			}
		} else {
			$this->view->brochure($firmid);
			$this->render('brochure');
		}
	}
	
	public function uploadLogo($firmid = 0) {
		$firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId(1);
		if (empty($_SESSION['firmid']) && ($firmid == 0 || !isset($_SESSION['access']['brochure']))) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}
		
		if (!empty($_FILES['choose_logo']['name'])) {
			include HELPERS_DIR.'upload/upload.php';
			$upload = new Upload($_FILES['choose_logo']);
			$upload->file_new_name_body = (string) $firmid;
			$upload->file_overwrite = true;
			$upload->Process(WT_PATH.'upload/firms_logo/');
			if (!$upload->processed) {
				echo "Erreur lors de l'upload de l'image à la une : ".$upload->error;
			} else {
				$dest = $upload->file_dst_name;
				include HELPERS_DIR.'SimpleImage/SimpleImage.php';
				$image = new SimpleImage();
				$image->load(WT_PATH.'upload/firms_logo/'.$dest);
				
				if ($image->getWidth()>$image->getHeight()) {
					$image->resizeToWidth(400);
					$image->save(WT_PATH.'upload/firms_logo/'.$firmid.'.png');
					$image->resizeToWidth(125);
					$image->save(WT_PATH.'upload/firms_logo/thumb_'.$firmid.'.png');
				} else {
					$image->resizeToHeight(400);
					$image->save(WT_PATH.'upload/firms_logo/'.$firmid.'.png');
					$image->resizeToHeight(125);
					$image->save(WT_PATH.'upload/firms_logo/thumb_'.$firmid.'.png');
				}				
			}
		}
	}

    public function pub() {
        $firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId(1);
		if (empty($_SESSION['firmid']) && ($firmid == 0 || !isset($_SESSION['access']['brochure']))&& (!isset($_SESSION['userid']) || $_SESSION['userid']!=1169)) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}
		
		// Les notes
		WNote::treatNoteSession();

        if($this->model->testPub($firmid)) {

            $data = WRequest::get(array( 'maudit_ie6'), null, 'POST', false);
		    if (!in_array(null, $data, true)) {
             if($data['maudit_ie6']) {
			    	$this->uploadBrochure($firmid);
			    }
         }
		    $this->view->pub($firmid);
		    $this->render('pub');	
        } else {
            WNote::error("Accès interdit","Vous devez d'abord réserver une page de pub dans la brochure depuis l'application 'Réservez votre stand'", 'display');
        }
    }

    public function uploadBrochure($firmid = 0) {
		$firmid = !empty($_SESSION['firmid']) ? $_SESSION['firmid'] : $this->getId(1);
		if (empty($_SESSION['firmid']) && ($firmid == 0 || !isset($_SESSION['access']['brochure']))) {
			WNote::error("Accès interdit", "Vous devez être connecté avec un compte entreprise pour accéder à cette zone.", 'display');
			return;
		}
		
		if (!empty($_FILES['choose_pub']['name'])) {
			include HELPERS_DIR.'upload/upload.php';
			$upload = new Upload($_FILES['choose_pub']);
			$upload->file_new_name_body = (string) $firmid;
			$upload->file_overwrite = true;
			$upload->Process(WT_PATH.'upload/pubBrochure/2013/');
			if (!$upload->processed) {
				WNote::error("Erreur lors de l'upload","Erreur lors de l'upload du pdf : ".$upload->error, 'display');
			} else {
                if(file_exists(WT_PATH."upload/pubBrochure/2013/".$firmid.".jpg")) {
                    unlink(WT_PATH."upload/pubBrochure/2013/".$firmid.".jpg");
                }
                exec("convert ".WT_PATH."upload/pubBrochure/2013/".$firmid.".pdf ".WT_PATH."upload/pubBrochure/2013/".$firmid.".jpg");
            }			
		}
	}
}

?>
