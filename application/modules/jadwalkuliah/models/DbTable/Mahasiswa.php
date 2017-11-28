<?php 
class Akademik_Model_DbTable_Mahasiswa extends Zend_Db_Table_Abstract {
	
	protected $_name="mahasiswa";
	protected $lobjDbAdpt;
	
	public function init(){
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function addData($data) {
		return $this->lobjDbAdpt->insert($this->_name,$data);
	}
	
	public function deleteData($id) {
		
		$this->lobjDbAdpt->delete($this->_name,'Idmhs='.$id);
	}
	
	public function updateData($data,$id) {
		//echo var_dump($data);echo $id;exit;
		$this->lobjDbAdpt->update($this->_name,$data,'idmhs='.$id);
		
	}
	
	public function getData($data=null) {
		
		//$select='select * from mahasiswa where nim="'.$data['nim'].'"';
		$select=$this->lobjDbAdpt->select()
		->from(array('a'=>$this->_name));
		//->join(array('b'=>'krs'),'a.nim=b.nim',array());
		if (isset($data['nim'])) {
			if ($data['nim']!=null) $select->where('a.nim=?',$data['nim']);
			//if ($data['idmhs']!=null) $select->where('a.idmhs=?',$data['idnim']);
			//if ($data['nama']!=null) $select->where('a.nama like %'.$data['nama'].'%');
		} else 
		if (isset($data['nama'])) {
			if ($data['nim']!=null) $select->where('a.nama like "%?%"',$data['nama']);
			//if ($data['idmhs']!=null) $select->where('a.idmhs=?',$data['idnim']);
			//if ($data['nama']!=null) $select->where('a.nama like %'.$data['nama'].'%');
		} else 
			if (isset($data['IdProgram'])) {
				if ($data['IdProgram']!=null) $select->where('a.IdProgram=?',$data['IdProgram']);
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