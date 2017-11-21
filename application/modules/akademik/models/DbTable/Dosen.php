<?php 
class Akademik_Model_DbTable_Dosen extends Zend_Db_Table_Abstract {
	
	protected $_name="dosen";
	protected $lobjDbAdpt;
	
	public function init(){
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function addData($data) {
		$this->lobjDbAdpt->insert($this->_name,$data);
	}
	
	public function deleteData($id) {
		$this->lobjDbAdpt->delete($this->_name,'IdDosen='.$id);
	}
	
	public function updateData($data,$id) {
		$this->lobjDbAdpt->update($this->_name,$data,'IdDosen='.$id);
		
	}
	
	public function getData($data=null) {
		
		//$select='select * from mahasiswa where nim="'.$data['nim'].'"';
		$select=$this->lobjDbAdpt->select()
		->from(array('a'=>$this->_name));
		//->join(array('b'=>'krs'),'a.nim=b.nim',array());
		if (isset($data['IdDosen'])) {
			if ($data['IdDosen']!=null) $select->where('a.IdDosen=?',$data['idDosen']);
			//if ($data['idmhs']!=null) $select->where('a.idmhs=?',$data['idnim']);
			//if ($data['nama']!=null) $select->where('a.nama like %'.$data['nama'].'%');
		} else 
		if (isset($data['Nama'])) {
			if ($data['Nama']!=null) $select->where('a.Nama like "%?%"',$data['Nama']);
			//if ($data['idmhs']!=null) $select->where('a.idmhs=?',$data['idnim']);
			//if ($data['nama']!=null) $select->where('a.nama like %'.$data['nama'].'%');
		}  
		//echo $select;exit;
		$row=$this->lobjDbAdpt->fetchAll($select);
		//echo var_dump($row);exit;
		return $row;
	}
}


?>