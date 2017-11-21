<?php 

class App_Model_Application_DbTable_ApplicantTempUsmSelection extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_temp_usm_selection';
	protected $_primary = "ats_id";
	
	public function addData($data){		
	   $id = $this->insert($data);
	   return $id;
	}
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	public function deleteData($id){		
	  $this->delete($this->_primary .' =' . (int)$id);
	}

	public function getApplicant($program,$preference){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
				     ->from(array('ats'=>$this->_name) )
			         ->where("ats.ats_program_code = '".$program."'")
			         ->where("ats.ats_preference = '".$preference."'");
		$row = $db->fetchAll($select);
		return $row;
	}
	
	public function deleteTransaction($txn_id){		
	  $this->delete("ats_transaction_id ='".$txn_id."'");
	}
}
?>