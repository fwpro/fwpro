<?php
class Mahasiswa_Model_DbTable_Mahasiswa extends Zend_Db_Table_Abstract {
    /**
     * The default table name
     */
    protected $_name = 'MAHASISWA';
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
        
        $this->lobjDbAdpt->update($this->_name,$data,'nim="'.$id.'"');
        //return parent::update($data, $where);
    }
    
    public function deleteData($id) {
        $this->lobjDbAdpt->delete($this->_name,'nama="'.$id.'"');
        
        //return parent::delete($where);
    }
    
    
    
    public function getData($data=null) {
        
        $select =$this->lobjDbAdpt->select()
        ->from(array('a'=>$this->_nama));
        
        if (isset($data['nimmhs'])) {
            if ($data['nimmhs']!=null) $select->where('a.NIM=?',$data['nimmhs']);
        }
        
        else if (isset($data['namamhs'])) {
            if ($data['namamhs']!=null) $select->where('a.NAME like "%?%"',$data['namamhs']);
        }
        
        else if (isset($data['prodimhs'])) {
            if ($data['prodimhs']!=null) $select->where('a.PRODI like "%?%"',$data['prodimhs']);
        }
        
        else if (isset($data['sexmhs'])) {
            if ($data['sexmhs']!=null) $select->where('a.SEX like "%?%"',$data['sexmhs']);
        }
        
        else if (isset($data['alamatmhs'])) {
            if ($data['alamatmhs']!=null) $select->where('a.ALAMAT=?',$data['alamatmhs']);
        }
        
        else if (isset($data['agamamhs'])) {
            if ($data['agamamhs']!=null) $select->where('a.AGAMA=?',$data['agamamhs']);
        }
        
        else if (isset($data['angkatanmhs'])) {
            if ($data['angkatanmhs']!=null) $select->where('a.ANGKATAN=?',$data['angkatanmhs']);
        }
        
        
        $row=$this->lobjDbAdpt->fetchAll($select);
        return $row;
    }
    
}


//test
