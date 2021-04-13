<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Julien1619
 * @version	$Id: apps/brochure/front/main.php 0001 11-06-2011 Julien1619 $
 */

class ApiController extends WController {

	public function __construct() {
		include 'model.php';
		$this->model = new ApiModel();
		
		include 'view.php';
		$this->setView(new ApiView($this->model));
	}
	
	/**
	 * Récupération de l'id fourni en Url
	 * @param void
	 * @return int
	 */
	
	public function launch() {
		$action = $this->getAskedAction();
		$this->forward($action, 'api');
	}
	
	public function api() {
            
            $command = $_REQUEST["command"];
            
            $result = "";
            switch ($command){
                case "authUser":
                    $result = $this->model->authUser($_REQUEST["login"],$_REQUEST["password"]);
                    break;
                
                case "getEntreprises":
                    $result = $this->model->getEntreprises();
                    break;
                
                case "getEntrepriseDetails":
                    $result = $this->model->getEntrepriseDetails($_REQUEST["entrepriseId"]);
                    break;
                
                case "getEntreprisesCategories":
                    $result = $this->model->getEntreprisesCategories();
                    break;
                
                case "authVisiteur":
                    $result = $this->model->authVisiteur($_REQUEST["mail"]);
                    break;
                case "getUniversities":
                    $result = $this->model->getUniversities();
                    break;
            }

            /*
            $data = WRequest::get(array( 'name', 'cat', 'website', 'creation_date', 'f1', 'f2', 'f3', 'f4','f5', 'pdg', 
                    'ca', 'adress','city','postal_code','country', 'co', 'implantation', 
                    'effectifs', 
                    'marques', 'contact', 'tel',
                    'presentation', 'savoir', 'etu'), null, 'POST', false); //'adress', 'city', 'postal_code', 'country'



            $this->model->writeApi($firmid, $data);
                    $this->view->brochure($firmid);
                    $this->render('brochure');
             * */
             echo json_encode(array("success"=>(bool) $result, "details"=>$result));
             die();

	}

}

?>
