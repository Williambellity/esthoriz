<?php
/**
 * Wity CMS
 * Système de gestion de contenu pour tous.
 *
 * @author Fofif
 * @version	$Id: view.php 0001 09-04-2011 Fofif $
 */

class TestView extends WView {
	private $model;
	
	public function __construct(TestModel $model) 
	{
		parent::__construct();
		$this->model = $model;
	}
	
	/*public function test() {
		$newscount = $this->model->test();
		$this->assign('newscount', $newscount);
	}*/
}

?>