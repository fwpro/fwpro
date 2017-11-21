<?php 

class App_Model_Application_DbTable_Secondtest extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_transaction';
	protected $_primary = "at_trans_id" ;
	
	function getCandidate($intake=84){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$sel = $db->select()
				->from(array('at'=>$this->_name))
				->join(array('ap'=>'applicant_profile'),"at.at_appl_id=ap.appl_id")
				->join(array('ti'=>'tbl_intake'),"at.at_intake=ti.IdIntake")
				->join(array('prd'=>'tbl_academic_period'), 'prd.ap_id = at.at_period')
				->where("at_status = 'PROCESS'")
				->where("at_selection_status = 5")
				->where("at_intake = ?",$intake);
		
		$row = $db->fetchAll($sel);
		
		//filtering condition & policy
		if($row){
			
			foreach ($row as $index=>$candidate){
				
				//tpa assigned but not yet happen
				$sel2 = $db->select()
						->from(array('apt'=>'applicant_ptest'))
						->join(array('aph'=>'appl_placement_head'), 'aph.aph_placement_code = apt.apt_ptest_code and aph.aph_testtype = 1')
						->join(array('aps'=>'appl_placement_schedule'), 'aps.aps_id = apt.apt_aps_id')
						->where('apt.apt_at_trans_id = ?',$candidate['at_trans_id'])
						->where('aps.aps_test_date > now()');
				
				$row2 = $db->fetchAll($sel2);
				
				if($row2){
					unset($row[$index]);
				}
				
				
			
				//policy assign 2 times for tpa test but not attend
				$sel3 = $db->select()
							->from(array('apt'=>'applicant_ptest'))
							->join(array('aph'=>'appl_placement_head'), 'aph.aph_placement_code = apt.apt_ptest_code and aph.aph_testtype = 1')
							->join(array('aps'=>'appl_placement_schedule'), 'aps.aps_id = apt.apt_aps_id')
							->where('apt.apt_usm_attendance = 0')
							->where('apt.apt_at_trans_id = ?',$candidate['at_trans_id'])
							->where('aps.aps_test_date < now()');
				
				$row3 = $db->fetchAll($sel3);
				
				if(sizeof($row3) >=2 ){
					unset($row[$index]);
				}
				
				
				//test taken
				$sel4 = $db->select()
				->from(array('apt'=>'applicant_ptest'))
				->join(array('aph'=>'appl_placement_head'), 'aph.aph_placement_code = apt.apt_ptest_code and aph.aph_testtype = 1')
				->join(array('aps'=>'appl_placement_schedule'), 'aps.aps_id = apt.apt_aps_id')
				->where('apt.apt_usm_attendance = 1')
				->where('apt.apt_at_trans_id = ?',$candidate['at_trans_id']);
				
				$row4 = $db->fetchAll($sel4);
				
				if($row4){
					unset($row[$index]);
				}
			}
			
			
		
		}
		
		return $row;				
		
	}
}
?>