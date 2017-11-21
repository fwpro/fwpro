<?php 
class App_Model_Application_DbTable_ApplicantPlacementScheduleTime extends Zend_Db_Table_Abstract
{
    protected $_name = 'appl_placement_schedule_time';
	protected $_primary = "apst_id";
	

	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		    $select = $db ->select()
						->from(array('apst'=>$this->_name))
						->where($this->_primary .' = '. (int)$id);
 			$row = $db->fetchRow($select);
			return $row;
	}
	
	public function getPtScheduleTime($schedule_id, $type_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
						->from(array('apst'=>$this->_name))
						->where('apst.apst_aps_id = '. (int)$schedule_id)
						->where('apst.apst_test_type = '. (int)$type_id);
						
 		$row = $db->fetchRow($select);
		
 		if($row){
 			return $row;
 		}else{
 			return false;
 		}
		
	}
	
	public function addData($data){
		$this->insert($data);
	}
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	public function deleteData($id){		
		$this->delete($this->_primary .' = ' . (int)$id);
	}
		
}
?>