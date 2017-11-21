<?php
class Akademik_IndexController extends Zend_Controller_Action {

	private $gstrsessionSIS;//Global Session Name
	public function init() { //instantiate log object

	}

	public function indexAction() {

		$this->view->title='DAFTAR IPK MAHASISWA SI <br> TEKNIK INFORMATIKA <br> USAKTI';
		$listofmhs=array(
						'0'=>array('NIM'=>'065001500088','NAMA'=>'Yohana Marpaung','IPK'=>'3.1'),
		 				'1'=>array('NIM'=>'065001400109','NAMA'=>'Bambang Renggowo','IPK'=>'2.5'),
		 				'2'=>array('NIM'=>'065001400189','NAMA'=>'Sujono','IPK'=>'3,4'));
		
		$this->view->listOfMhs=$listofmhs;
		  
	}
		
	}

	


