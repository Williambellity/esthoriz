<?php



class QumanageController extends WController {

	public function __construct() {

		include 'model.php';

		$this->model = new QumanageModel();

		

		include 'view.php';

		$this->setView(new QumanageView($this->model));

	}

	

	public function launch() {


        $this->view->qumanage();

        $this->render('qumanage');

	}


}



?>

