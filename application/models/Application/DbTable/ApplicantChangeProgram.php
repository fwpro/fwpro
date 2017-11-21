<?php 

class App_Model_Application_DbTable_ApplicantChangeProgram extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_change_program';
	protected $_primary = "acp_id";
	
	public function getData($id=null){
	
		$select = $this->_db->select()->from($this->_name);
			
		if($id)	{
			$select->where("acp_id ='".$id."'");
			
			$row = $this->_db->fetchRow($select);
	
		}else{
			$row = $this->_db->fetchAll($select);
		}
		
		if(!$row){
			return null;
		}else{
			return $row;
		}
	}
	
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
	
	public function getInfo($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('acp'=>$this->_name))							 		  
					  ->where("acp.acp_trans_id = '".$transaction_id."'");
									  
		 $row = $db->fetchRow($select);	
		 
		 if($row){
		    return $row;
		 }else{
		 	return null;
		 }
	}
	
	public function getChangeProgram($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('acp'=>$this->_name))		
					  ->joinLeft(array('ap'=>'applicant_program'),'ap.ap_at_trans_id=acp.acp_trans_id_to')
					  ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code')
					  ->joinLeft(array('t'=>'applicant_transaction'),'t.at_trans_id=acp.acp_trans_id_to')
					  ->where("acp.acp_trans_id_to = '".$transaction_id."'");
									  
		 $row = $db->fetchRow($select);	
		 
		 if($row){
		    return $row;
		 }else{
		 	return null;
		 }
	}
	
	
	public function geListApplication(){
		
		/*
		 * SELECT ap.appl_id,ap.appl_fname,acp.*
			FROM `applicant_change_program` as acp
			left join applicant_profile as ap
			ON ap.appl_id=acp.acp_appl_id
		 */
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('acp'=>$this->_name))		
					  ->joinLeft(array('ap'=>'applicant_profile'),'ap.appl_id=acp.acp_appl_id')	;	 
									  
		 $row = $db->fetchAll($select);	
		 
		 if($row){
		    return $row;
		 }else{
		 	return null;
		 }
	}
	
	public function gePaginateListApplication(){
		
		/*
		 * SELECT ap.appl_id,ap.appl_fname,acp.*
			FROM `applicant_change_program` as acp
			left join applicant_profile as ap
			ON ap.appl_id=acp.acp_appl_id
		 */
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('acp'=>$this->_name))		
					  ->joinLeft(array('ap'=>'applicant_profile'),'ap.appl_id=acp.acp_appl_id')
					  ->order('acp.acp_createddt desc');	 
									  
		return $select;
	}
}