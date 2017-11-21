<?php 

class App_Model_Application_DbTable_ApplicantAssessmentUsmDetl extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_assessment_usm_detl';
	protected $_primary = "aaud_id";
	
	public function addData($data){		
	   $id =  $this->insert($data);
	   return $id;
	}
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	public function deleteData($id){		
	  $this->delete($this->_primary .' =' . (int)$id);
	}
	
	public function getData($id=null){
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
			->from(array('aaud'=>$this->_name))
			->joinleft(array('ay'=>'tbl_academic_year'),'ay.ay_id=aaud.aaud_academic_year',array('ay_code'))
			->order('aaud.aaud_id desc')
			->group("aaud.aaud_nomor");
	
		if($id){
			$select->where('aaud.aaud_id =?',$id);
			$row = $db->fetchRow($select);
		}else{
			$row = $db->fetchAll($select);
		}
		
		return $row;
	}

	public function getPaginateNomor(){		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aaud'=>$this->_name))
					  ->joinleft(array('ay'=>'tbl_academic_year'),'ay.ay_id=aaud.aaud_academic_year',array('ay_code'))					
					  ->order('aaud.aaud_id desc')
					  ->group("aaud.aaud_nomor");
				  
		$row = $db->fetchAll($select);
		return $row;
	}
	
	public function getNomor(){		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aaud'=>$this->_name))
					  ->order('aau.aau_id desc')
					  ->group("aaud.aaud_nomor");
					  
		
		return $row;
	}
	
	public function getLockData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aaud'=>$this->_name),array('date'=>'aaud_decree_date'))
					  ->where("aaud.aaud_lock_status=1");
		$row = $db->fetchAll($select);
		return $row;
	}
	
	public function getUnlockNomor(){		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aaud'=>$this->_name))
					  ->joinleft(array('ay'=>'tbl_academic_year'),'ay.ay_id=aaud.aaud_academic_year',array('ay_code'))	
					  ->where("aaud.aaud_lock_status!=1")				
					  ->order('aaud.aaud_id desc')
					  ->group("aaud.aaud_nomor");
				  
		$row = $db->fetchAll($select);
		return $row;
	}
	
	public function updateStatus($data,$nomor){
		 $this->update($data, "aaud_nomor = '".$nomor."'");
	}
}
?>