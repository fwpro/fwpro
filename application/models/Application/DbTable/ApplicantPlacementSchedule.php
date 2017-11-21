<?php 
class App_Model_Application_DbTable_ApplicantPlacementSchedule extends Zend_Db_Table_Abstract
{
    protected $_name = 'appl_placement_schedule';
	protected $_primary = "aps_id";
	
	
	public function getInfo($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		    $select = $db ->select()
						->from(array('aps'=>$this->_name))
						->where("aps_test_date > curdate()");

			//echo $select;
									
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
		    return $row;
	}
	
	
	public function getLocationByDate($date){
		
			$db = Zend_Db_Table::getDefaultAdapter();
		
		    $select = $db ->select()
						->from(array('aps'=>$this->_name))
						->joinLeft(array('l'=>'appl_location'),'l.al_id=aps.aps_location_id ',array('location_id'=>'l.al_id','location_name'=>'l.al_location_name'))
						->where("aps.aps_test_date = '".$date."'");						
						
	       
	        $row = $db->fetchAll($select);
			return $row;
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
		
	
	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		    $select = $db ->select()
						->from(array('aps'=>$this->_name))
						->where($this->_primary .' = '. (int)$id);
 			$row = $db->fetchRow($select);
			return $row;
	}
	
	public function getPtestDate($ptest='', $today=false){
			
		$db = Zend_Db_Table::getDefaultAdapter();
	  	$select = $db->select()
	                 ->from(array('aps'=>'appl_placement_schedule'))
					 ->where("aps_placement_code = '".$ptest."'")
					 ->group("aps_test_date");
		
		if($today==true){
			$select->where("aps_test_date <= curdate()");
		}else{
			$select->where("aps_test_date < curdate()");
		}
	    
        $row = $db->fetchAll($select);
        return $row;
	}
	
	public function getAvailableDate($appl_id=0, $txn_id=0){
			
			$db = Zend_Db_Table::getDefaultAdapter();
			
			
			/*SELECT *  
	FROM `applicant_transaction` AS  at
	LEFT JOIN applicant_ptest as apt
	ON apt.apt_at_trans_id=at.at_trans_id
	LEFT JOIN appl_placement_schedule as aps
	ON aps.aps_id=apt.apt_aps_id
	WHERE `at_appl_id` = 50 AND `at_appl_type` = 1 and
	at.at_status !='APPLY'*/
			
		 	$select_date = $db ->select()
						->from(array('at'=>'applicant_transaction'),array())
						->join(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',array())
						->join(array('aps'=>'appl_placement_schedule'),'aps.aps_id=apt.apt_aps_id',array('aps_test_date'=>'distinct(aps.aps_test_date)'))
						->where("at_appl_id= '".$appl_id."'")						
						->where("at_appl_type = 1");
						
			if($txn_id!=0){
				$select_date->where("at_trans_id != '".$txn_id."'");
			}			
		
		    $select = $db ->select()
						->from(array('aps'=>$this->_name))
						->where("aps_test_date > curdate()")
						->where("aps_test_date NOT IN (?)",$select_date);

			//echo $select;
									
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
		    return $row;
	}
	
	
}
?>