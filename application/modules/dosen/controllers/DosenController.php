<?php
class Dosen_DosenController extends Zend_Controller_Action {
    
    public function dosenAction() {
        //blok 1
        $this->view->nama="Miqdad";
        $dbDsn = new Dosen_Model_DbTable_Dosen();
        $biodata=array();
        $biodatas=array();
        if ($this->getRequest ()->isPost ()) {
            //blok 2
            $data = $this->getRequest()->getPost();
            //echo var_dump($data);exit;
            
            if (isset($data['Search'])) {
                $biodata=$dbDsn->getData($data);
                
                $this->view->biodata=$biodata;
            }
            
            else if (isset($data['Save'])) {
                $biodata=array('nik'=>$data['nikdsn'],
                    'nama'=>$data['namadsn'],
                    'alamat'=>$data['alamatdsn'],
                    'sex'=>$data['sexdsn'],
                    'agama'=>$data['agamadsn'],
                    'tgl_lahir'=>$data['tgldsn'],
                    'tempat_lahir'=>$data['tempatdsn'],
                );
                
                if($data['iddsn']=='') $dbDsn->addData($biodata);
                
                else {
                    
                    $iddsn=$data['iddsn'];
                    
                    $dbDsn->updateData($biodata, $iddsn);
                }
                
                
            }
            
            else if (isset($data['delete'])) {
                $iddsn=$data['iddsn'];
                $dbDsn->deleteData($iddsn);
                
            }
            
            $data = $this->getRequest()->getPost();
            $biodata=array('nik'=>$data['nikdsn'],
                'nama'=>$data['namadsn'],
                'alamat'=>$data['alamatdsn'],
                'sex'=>$data['sexdsn'],
                'agama'=>$data['agamadsn'],
                'tgl_lahir'=>$data['tgldsn'],
                'tempat_lahir'=>$data['tempatdsn'],
            );
        }
        //blok 3
        $biodatas=$dbDsn->getData();
        $this->view->biodatas=$biodatas;
        
    }
    
}
?>
