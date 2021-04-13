<?php



class ConseilstestController extends WController {

	public function __construct() {

		include 'model.php';

		$this->model = new ConseilstestModel();

		

		include 'view.php';

		$this->setView(new ConseilstestView($this->model));

	}

	

	public function launch() {


        $this->view->conseilstest();

        $this->render('conseils');

	}


}



?>

