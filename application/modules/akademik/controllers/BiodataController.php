<?php 
class Akademik_BiodataController extends Zend_Controller_Action {
	
	public function mahasiswaAction() {
		//blok 1
		$this->view->nama="Agung Sediyono";
		$dbMhs=new Akademik_Model_DbTable_Mahasiswa();
		$biodata=array();
		$biodatas=array();
		if ($this->getRequest ()->isPost ()) {
			//blok 2
			$data = $this->getRequest()->getPost();
			//echo var_dump($data);exit;
			if (isset($data['Search'])) {
				$biodata=$dbMhs->getData($data);
				//echo var_dump($biodata);exit;
				$this->view->biodata=$biodata;
			} else if (isset($data['Save'])) {
				$biodata=array('Nim'=>$data['nim'],
								'Nama'=>$data['namamhs'],
								'Alamat'=>$data['alamat']);
				
				if ($data['idmhs']=='' ) $dbMhs->addData($biodata);
				else {
					 
					$idmhs=$data['idmhs'];
					  
					$dbMhs->updateData($biodata, $idmhs);
				}
				$biodatas=$dbMhs->getData();
			} else if (isset($data['Delete'])) {
				$idmhs=$data['idmhs'];
				$dbMhs->deleteData($idmhs);
			} 
			
		}
		//blok 3
		 
		 $this->view->biodatas=$biodatas;
		 
	}
}


?>