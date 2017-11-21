<?php
class Akademik_RecordController extends Zend_Controller_Action {

	private $gstrsessionSIS;//Global Session Name
	public function init() { //instantiate log object

	}

	public function listMahasiswaAction() {
		$this->view->title='Entry Mahasiswa';
		$dbMhs=new Akademik_Model_DbTable_Mahasiswa();
		
		if ($this->getRequest ()->isPost ()) {
			 
				$data = $this->getRequest()->getPost();
				echo var_dump($data);exit;
				if (isset($data['cari'])) {
					$row1=$dbMhs->getData($data);
					//echo var_dump($row);exit;
					
					$this->view->nama=$row1[0]['Nama'];
					$this->view->nim=$row1[0]['Nim'];
					$this->view->idmhs=$row1[0]['Idmhs'];
				}
				if (isset($data['add'])){
					$dbMhs->addData(array('Nim'=>$data['nim'],'Nama'=>$data['nama']));
				}
				if (isset($data['del']))  {
					$dbMhs->deleteData($data['IdMhs']);
				}
				if (isset($data['update'])) {
					$updata=array('nim'=>$data['nim'],'Nama'=>$data['nama']);
					$dbMhs->updateData($updata,$data['IdMhs']);
				}
				//echo var_dump($data);exit;
				
		 
		}
		$row=$dbMhs->getData();
		$this->view->listnama=$row;
				
	}
	public function daftarMahasiswaAction() {
		$this->view->title='Daftar Mahasiswa';
		$dbMhs=new Akademik_Model_DbTable_Program();
		$dbDosen=new Akademik_Model_DbTable_Dosen();
	 	$this->view->programlist=$dbMhs->getDatabyCollege();
	 	$this->view->dosenlist=$dbDosen->getData();
	 	$dbColl=new Akademik_Model_DbTable_College();
	 	$this->view->collegelist=$dbColl->getDatabyCollege();
	
	}
		
	public function ajaxGetMahasiswaAction(){
	
		$this->_helper->layout()->disableLayout();
	
		$idprogram = $this->_getParam('idprogram', null);
		$dbMhs=new Akademik_Model_DbTable_Mahasiswa();
		$row=$dbMhs->getData(array('IdProgram'=>$idprogram));
			
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('view', 'html')
		->addActionContext('form', 'html')
		->addActionContext('process', 'json')
		->initContext();
	
		$json = Zend_Json::encode($row);
	
		echo $json;
		exit();
			
	}
	public function ajaxGetProgramAction(){
	
		$this->_helper->layout()->disableLayout();
	
		$idcollege = $this->_getParam('idcollege', null);
		$dbProgram=new Akademik_Model_DbTable_Program();
		$row = $dbProgram->getDatabyCollege($idcollege);
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('view', 'html')
		->addActionContext('form', 'html')
		->addActionContext('process', 'json')
		->initContext();
	
		$json = Zend_Json::encode($row);
	
		echo $json;
		exit();
			
	}
		
	}
