<?php



class ConseilsController extends WController {

	public function __construct() {

		include 'model.php';

		$this->model = new ConseilsModel();

		

		include 'view.php';

		$this->setView(new ConseilsView($this->model));

	}

	

	public function launch() {


        $this->view->conseils();

        $this->render('conseils');

	}


}



?>

