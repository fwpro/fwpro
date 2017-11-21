<?php 

class App_Model_Application_DbTable_ApplicantQuit extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_quit';
	protected $_primary = "aq_id";
	
	public function getData($id=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aq'=>$this->_name))
					  ->join(array('at'=>'applicant_transaction'), 'at.at_trans_id = aq.aq_trans_id')
					  ->joinLeft(array('lkp'=>'tbl_definationms'), 'aq.aq_reason = lkp.idDefinition', array('quit_reason'=>'BahasaIndonesia'))
					  ->order("aq_id desc");
					  
		if($id)	{			
			 $select->where("aq.aq_id ='".$id."'");
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
	
	public function updateQuit($data,$txnid){
		 $this->update($data,'aq_trans_id = '. (int)$txnid);
	}
	
	public function getInfo($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aq'=>$this->_name))					  
					  ->where("aq.aq_trans_id = '".$transaction_id."'");
									  
		 $row = $db->fetchRow($select);	
		 
		 if($row){
		    return $row;
		 }else{
		 	return null;
		 }
	}
	
	public function getChequeInfo($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('aq'=>$this->_name))	
					  ->joinLeft(array('rc'=>'refund_cheque'),'rc.rchq_id=aq.aq_cheque_id')	
					  ->joinLeft(array('d'=>'tbl_definationms'),'d.idDefinition=rc.rchq_bank_name',array('BahasaIndonesia'))			  
					  ->where("aq.aq_trans_id = '".$transaction_id."'");
									  
		 $row = $db->fetchRow($select);	
		 
		 if($row){
		    return $row;
		 }else{
		 	return null;
		 }
	}
	
	
}