<?php



class QuestionsController extends WController {

	public function __construct() {

		include 'model.php';

		$this->model = new QuestionsModel();

		

		include 'view.php';

		$this->setView(new QuestionsView($this->model));

	}

	

	public function launch() {
        if(isset($_POST['issent'])){
            $sender_message = substr(stripslashes(strtr($_POST["bod"], "éèàùï", "eeaui")),0,300);
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
                if(strlen($sender_message)==0){
                    WNote::error("Erreur", "message vide", 'display');
                }
                else{
                    $this->model->addMessage($sender_message);
                    WNote::success("questions", "Votre question a été enregistrée", 'display');
                }
            }
            else{
                WNote::error("Erreur", "captcha invalide", 'display');
            }
        }
        else{
        $this->view->Questions();

        $this->render('questions');
        }
	}


}



?>

