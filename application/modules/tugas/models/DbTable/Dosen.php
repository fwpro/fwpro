<?php
class Tugas_Model_DbTable_Dosen extends Zend_Db_Table_Abstract {
    /**
     * The default table name
     */
    protected $_name = 'Dosen';
    protected $lobjDbAdpt;
    
    public function init() {
        
        $this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
    }
    
    public function addData($data) {
        $this->lobjDbAdpt->insert($this->_name,$data);
        
        //return parent::insert($data);
    }
    
    public function updateData($data,$id) {
        //echo var_dump($data); echo $id; exit;
        
        $this->lobjDbAdpt->update($this->_name,$data,'nik="'.$id.'"');
        //return parent::update($data, $where);
    }
    
    public function deleteData($id) {
        $this->lobjDbAdpt->delete($this->_name,'nik="'.$id.'"');
        
        //return parent::delete($where);
    }
    
    
    
    public function getData($data=null) {
        
        $select =$this->lobjDbAdpt->select()
        ->from(array('a'=>$this->_name));
        
        if (isset($data['nikdsn'])) {
            if ($data['nikdsn']!=null) $select->where('a.nik=?',$data['nikdsn']);
        }
        
        else if (isset($data['namadsn'])) {
            if ($data['namadsn']!=null) $select->where('a.name like "%?%"',$data['namadsn']);
        }
        
        else if (isset($data['alamatdsn'])) {
            if ($data['alamatdsn']!=null) $select->where('a.alamat like "%?%"',$data['alamatdsn']);
        }
        
        else if (isset($data['sexdsn'])) {
            if ($data['sexdsn']!=null) $select->where('a.sex like "%?%"',$data['sexdsn']);
        }
        
        else if (isset($data['agamadsn'])) {
            if ($data['agamadsn']!=null) $select->where('a.agama=?',$data['agamadsn']);
        }
        else if (isset($data['tgldsn'])) {
            if ($data['tgldsn']!=null) $select->where('a.tgl_lahir=?',$data['tgldsn']);
        }
        else if (isset($data['tempatdsn'])) {
            if ($data['tempatdsn']!=null) $select->where('a.tempat_lahir=?',$data['tempatdsn']);
        }
        $row=$this->lobjDbAdpt->fetchAll($select);
        return $row;
    }
    
}
