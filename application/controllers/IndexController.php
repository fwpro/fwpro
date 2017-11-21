<?php
class IndexController extends Zend_Controller_Action {

	private $gstrsessionSIS;//Global Session Name
	public function init() { //instantiate log object

	}

	public function indexAction() {
		$this->view->title='PEMROGRAMAN BERBASIS FRAMEWORK';
		$this->view->listnama=array('1'=>'jono','2'=>'joko');
	}
		
	}

	


