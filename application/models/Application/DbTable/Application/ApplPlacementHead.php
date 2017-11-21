<?php 
class App_Model_Application_DbTable_ApplPlacementHead extends Zend_Db_Table_Abstract {

	protected $_name = 'appl_placement_head';
	protected $_primary = "aph_id";
		
	public function getData($id=""){
	
		$select = $this->_db->select()
					  ->from($this->_name);
					  
		if($id)	{			
			 $select->where("aph_id ='".$id."'");
			 $row = $this->_db->fetchRow($select);	
			 
		}	else{			
			$row = $this->_db->fetchAll($select);	
		}	  
		
		 return $row;
	}
	
	public function getDataByCode($code=null, $type=null){
	
		$select = $this->_db->select()
				->from($this->_name);
		
		if($type!=null){
			$select->where('aph_testtype =?',$type);
		}
					  
		if($code)	{			
			 $select->where("aph_placement_code  ='".$code."'");
			 $row = $this->_db->fetchRow($select);	
			 
		}	else{			
			$row = $this->_db->fetchAll($select);	
		}	  
		
		 return $row;
	}
	
}

?>