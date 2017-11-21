<?php 

class App_Model_Application_DbTable_ApplicantQuitRevert extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_quit_revert';
	protected $_primary = "aqr_id";
	
	public function getData($id=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->order("aq_id desc");
					  
		if($id)	{			
			 $select->where("aq_id ='".$id."'");
			 $row = $db->fetchRow($select);				 
		}else{
			 $row = $db->fetchAll($select);			
		}	 
		
		if($row){
			return $row;
		}else{
			return null;
		}
		
	}
	
	
}