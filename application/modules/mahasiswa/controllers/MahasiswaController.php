<?php
class Mahasiswa_MahasiswaController extends Zend_Controller_Action {
    
    public function mahasiswaAction() {
        //blok 1
        $this->view->nama="Kelompok 1";
        $dbMhs = new Mahasiswa_Model_DbTable_Mahasiswa();
        $biodata=array();
        $biodatas=array();
        $this->view->biodata=array();
        if ($this->getRequest ()->isPost ()) {
            //blok 2
            $data = $this->getRequest()->getPost();
            //echo var_dump($data);exit;
            
            if (isset($data['Search'])) {
                $mahasiswa=$dbMhs->getData($data);
                
                $this->view->biodata=$biodata;
            }
            
            else if (isset($data['Save'])) {
                $mahasiswa=array('NIM'=>$data['nimmhs'],
                    'NAMA'=>$data['namamhs'],
                    'ALAMAT'=>$data['alamatmhs'],
                    'SEX'=>$data['sexmhs'],
                    'PRODI'=>$data['prodimhs'],
                    'AGAMA'=>$data['agamamhs'],
                    'ANGKATAN'=>$data['angkatanmhs']);
                
                if($data['idmhs']=='') $dbMhs->addData($biodata);
                
                else {
                    
                    $idmhs=$data['idmhs'];
                    
                    $dbMhs->updateData($biodata, $idmhs);
                }
                
                
            }
            
            else if (isset($data['delete'])) {
                $idmhs=$data['idmhs'];
                $dbMhs->deleteData($idmhs);
                
            }
            
            $data = $this->getRequest()->getPost();
            $biodata=array('NIM'=>$data['nimmhs'],'NAMA'=>$data['namamhs'],'ALAMAT'=>$data['alamatmhs'],'SEX'=>$data['sexmhs'],'PRODI'=>$data['prodimhs'],'AGAMA'=>$data['agamamhs'],'ANGKATAN'=>$data['angkatanmhs']);
        }
        //blok 3
        
        $biodatas=$dbMhs->getData();
       
        $this->view->biodatas=$biodatas;
      
    }
    
}
?>