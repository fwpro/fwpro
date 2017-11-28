<?php 
class Akademik_Model_DbTable_Program extends Zend_Db_Table_Abstract {
	
	protected $_name="tbl_program";
	protected $lobjDbAdpt;
	
	public function init(){
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function addData($data) {
		$this->lobjDbAdpt->insert($this->_name,$data);
	}
	
	public function deleteData($id) {
		$this->lobjDbAdpt->delete($this->_name,'IdProgram='.$id);
	}
	
	public function updateData($data,$id) {
		$this->lobjDbAdpt->update($this->_name,$data,'IdProgram='.$id);
		
	}
	
	public function getDatabyCollege($idcollege=null) {
		
		//$select='select * from mahasiswa where nim="'.$data['nim'].'"';
		$select=$this->lobjDbAdpt->select()
		->from(array('a'=>$this->_name));
		
		if ($idcollege!=null) $select->where('a.IdCollege=?',$idcollege);
		 
		$row=$this->lobjDbAdpt->fetchAll($select);
		//echo var_dump($row);exit;
		return $row;
	}
}


?>