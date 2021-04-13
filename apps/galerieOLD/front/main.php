<?php



class GalerieController extends WController {

	public function __construct() {

		include 'model.php';

		$this->model = new GalerieModel();

		

		include 'view.php';

		$this->setView(new GalerieView($this->model));

	}

	

	public function launch() {


        $this->view->galerie();

        $this->render('galerie');

	}


}



?>

