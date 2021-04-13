<?php



class ArticlesController extends WController {

	public function __construct() {

		include 'model.php';

		$this->model = new ArticlesModel();

		

		include 'view.php';

		$this->setView(new ArticlesView($this->model));

	}

	

	public function launch() {


        $this->view->articles();

        $this->render('articles');

	}


}



?>

