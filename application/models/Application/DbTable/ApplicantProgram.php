<?php 
class App_Model_Application_DbTable_ApplicantProgram extends Zend_Db_Table_Abstract
{
    protected $_name = 'applicant_program';
	protected $_primary = "ap_id";
	
	
	public function getPlacementProgram($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	     $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					//->joinLeft(array('d'=>'tbl_departmentmaster'),'p.idFacultyDepartment=d.IdDepartment',array('faculty'=>'d.DepartmentName'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'")				
					->order("ap.ap_preference Asc");
					
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
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
	
	public function deleteTransactionData($transaction_id){
		$this->delete('ap_at_trans_id =' . (int)$transaction_id);
	}
	
	
	public function deleteDataProgram($appl_id,$ptest_code){		
	  $this->delete("app_at_trans_id  = '".$appl_id."' AND app_ptest_code = '".$ptest_code."'");
	  
		//echo $sql ="DELETE FROM `trisakti_app`.`applicant_ptest_program` WHERE `applicant_ptest_program`.`app_id` = '$appl_id' AND app_ptest_code = '$ptest_code'";
	}
	
	
	public function getProcedure($transid,$program1,$program2,$scheduleid=1, $type=0){
		//echo 'ini program:'.$program1;
/*		$program1 = "0220";
		$program2 = "0210";
		$location = "1";*/
		$db = Zend_Registry::get('dbapp');
		
		//echo "CALL pr_ptest_roomseatno('".$program1."','".$program2."',$scheduleid,@vRoomId, @vSitNo)";
		
		if($type!=0){
			$stmt = $db->query("CALL pr_tpa_roomseatno($transid,'".$program1."','".$program2."',$scheduleid,@vRoomId, @vSitNo)");
			$stmt;
			$select = $db->query("SELECT @vRoomId as roomid,@vSitNo as sitno");
			$row = $select->fetchAll();
		}else{
			$stmt = $db->query("CALL pr_ptest_roomseatno($transid,'".$program1."','".$program2."',$scheduleid,@vRoomId, @vSitNo)");		
			$stmt;
			$select = $db->query("SELECT @vRoomId as roomid,@vSitNo as sitno");	 			
			$row = $select->fetchAll();
		}
		
		
		if($row[0]["roomid"]==0){
			$error="sit no";
		}						
		return $row;


	}
	
	
	
	public function getComponentSchedule($transaction_id){
		
		/*
		 * select distinct(ac_comp_name),aps_test_date, ac_start_time, al_location_name
  from applicant_program, appl_placement_weightage,appl_placement_detl,appl_component,
appl_placement_schedule,appl_location 
where ap_at_trans_id = 1
and ap_ptest_prog_id  = apw_app_id
and apw_apd_id =apd_id
and apd_comp_code = ac_comp_code
and aps_placement_code = apd_placement_code
and al_id = aps_location_id
		 */
		
	$db = Zend_Registry::get('dbapp');
		
	      $select = $db ->select()
	                ->distinct('c.ac_comp_name')
					->from(array('ap'=>$this->_name),array())					
					->joinLeft(array('w'=>'appl_placement_weightage'),'ap.ap_ptest_prog_id  = w.apw_app_id')
					->joinLeft(array('d'=>'appl_placement_detl'),'w.apw_apd_id = d.apd_id')					
					->joinLeft(array('c'=>'appl_component'),'d.apd_comp_code = c.ac_comp_code',array('ac_comp_name'=>'c.ac_comp_name'))					
					->joinLeft(array('s'=>'appl_placement_schedule'),'s.aps_placement_code = d.apd_placement_code',array('aps_test_date'=>'s.aps_test_date','aps_start_time'=>'s.aps_start_time'))
					->joinLeft(array('l'=>'appl_location'),'l.al_id = s.aps_location_id',array('al_location_name'=>'l.al_location_name'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'");		
					
					
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	
public function getComponentSchedulebytype($transaction_id,$com_type=1,$schedule_id=""){
		
		
		if($com_type==1){
			$where = "c.ac_test_type  = '".$com_type."'";
		}else{
			$where = "c.ac_test_type  <> 1";
		}
	$db = Zend_Db_Table::getDefaultAdapter();
		
	      $select = $db ->select()
	                ->distinct('c.ac_comp_name')
					->from(array('ap'=>$this->_name),array())					
					->joinLeft(array('w'=>'appl_placement_weightage'),'ap.ap_ptest_prog_id  = w.apw_app_id',array())
					->joinLeft(array('d'=>'appl_placement_detl'),'w.apw_apd_id = d.apd_id',array())					
					->joinLeft(array('c'=>'appl_component'),'d.apd_comp_code = c.ac_comp_code',array('ac_comp_name'=>'c.ac_comp_name','ac_comp_name_bahasa'=>'c.ac_comp_name_bahasa','ac_start_time'=>'c.ac_start_time'))					
					->joinLeft(array('s'=>'appl_placement_schedule'),'s.aps_placement_code = d.apd_placement_code',array('aps_test_date'=>'s.aps_test_date','aps_start_time'=>'s.aps_start_time'))
					->joinLeft(array('l'=>'appl_location'),'l.al_id = s.aps_location_id',array('al_location_name'=>'l.al_location_name'),array())
					->where("ap.ap_at_trans_id  = '".$transaction_id."' and s.aps_id = '".$schedule_id."'" )
					->where($where);	
	
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
        
	}
	
public function getApplicantProgram($program_code,$nationality=null){
		
		$db = Zend_Registry::get('dbapp');
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))						
					->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=ap.ap_at_trans_id')	
					->joinLeft(array('af'=> 'applicant_profile'),'af.appl_id=at.at_appl_id')				
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					->where('at.at_appl_type=2');//high school
										
		if($program_code){
			$select->where("ap.ap_prog_code  = '".$program_code."'");	
		}
		
		if($nationality==1){
			$select->where("af.appl_nationality  = '".$nationality."'");	
		}else{			
			$select->where("af.appl_nationality  != 1");	
		}		
					
		$stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	
	
	public function getApplicantProgramByID($transaction_id){
		
		$db = Zend_Registry::get('dbapp');
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))						
					->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=ap.ap_at_trans_id')	
					->joinLeft(array('af'=> 'applicant_profile'),'af.appl_id=at.at_appl_id')				
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					->where("at.at_trans_id='".$transaction_id."'");//high school
		
			$select->order('ap.ap_preference asc');
					
		$stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	

	public function getProgramFaculty($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					//->joinLeft(array('d'=>'tbl_departmentmaster'),'p.idFacucltDepartment=d.IdDepartment')
					->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.CollegeName','faculty2'=>'c.ArabicName', 'faculty_id'=>'IdCollege'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'")				
					->order("ap.ap_preference Asc");
				
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	
	public function getTotalStudent($program_code,$periode,$status){
						
		$db = Zend_Db_Table::getDefaultAdapter();
			
		$selectData = $db->select()
						->from(array('ap'=>$this->_name))						
						->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=ap.ap_at_trans_id')
						->where("at_status='".$status."'")
						->where("at_period= '". $periode."'")
						->where("ap_prog_code = '".$program_code."'");
						
		$stmt = $db->query($selectData);
        $row = $stmt->fetchAll();		
		return count($row);	
	}
	
	public function getTotalStudentByFaculty($faculty_id,$periode,$status){
						
		$db = Zend_Db_Table::getDefaultAdapter();
			
		 $selectData = $db->select()
						->from(array('ap'=>$this->_name))						
						->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=ap.ap_at_trans_id')
						->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
						->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.CollegeName'))
						->where("at.at_status='".$status."'")
						->where("at.at_period= '". $periode."'")
						->where("c.IdCollege = '".$faculty_id."'");
						
		$stmt = $db->query($selectData);
        $row = $stmt->fetchAll();		
		return count($row);	
	}
	
	public function getProgram($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
				
					->where("ap.ap_at_trans_id  = '".$transaction_id."'")				
					->order("ap.ap_preference Asc");
					
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	
	
	public function getData($id=""){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code');
					  
		if($id)	{			
			 $select->where("ap_id ='".$id."'");
			 $row = $db->fetchRow($select);	
			 
		}	else{			
			$row = $db->fetchAll($select);	
		}	  
		
		 return $row;
	}
	
	public function getUsmOfferProgram($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))				
					->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.CollegeName','faculty2'=>'c.ArabicName', 'faculty_id'=>'IdCollege'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'")
					->where("ap.ap_usm_status  = 1")				
					->order("ap.ap_preference Asc");
	
         $row = $db->fetchRow($select);	
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	
	public function getProgramPreference($transaction_id,$preference){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
				
					->where("ap.ap_at_trans_id  = '".$transaction_id."'")	
					->where("ap.ap_preference  = '".$preference."'")				
					->order("ap.ap_preference Asc");
					
         $row = $db->fetchRow($select);	
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
	}
	
	public function getProgramAssessment($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	      $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
				    ->joinLeft(array('aau'=>'applicant_assessment_usm'),'aau.aau_ap_id=ap.ap_id',array('aau.aau_rector_ranking','aau.aau_rector_status'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'")	
					->order("ap.ap_preference Asc");
					
         $row = $db->fetchAll($select);	
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
	}
	
	public function updateStatusData($data,$txn_id){
		$this->update($data, "ap_at_trans_id='".$txn_id."'");
	}
	
	public function getProgramOffered($transaction_id,$appl_type=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.CollegeName','faculty_indonesia'=>'c.ArabicName', 'faculty_id'=>'IdCollege'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'");			
		
				    //jika usm kene check program mana yg offer sebab usm ada 2 pilihan
				    if(isset($appl_type) && ($appl_type==1)){
						$select->where("ap.ap_usm_status  = 1");	
					}
					
        $row = $db->fetchRow($select);
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
	}
	
	
public function getOfferProgram($transaction_id,$appl_type=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.CollegeName','faculty_indonesia'=>'c.ArabicName', 'faculty_id'=>'IdCollege'))
					->where("ap.ap_at_trans_id  = '".$transaction_id."'");			
		
				    //jika usm kene check program mana yg offer sebab usm ada 2 pilihan
				    if(isset($appl_type) && ($appl_type==1)){
						$select->where("ap.ap_usm_status  = 1");	
					}
					
        $row = $db->fetchRow($select);
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
	}
	
	public function getChangeProgram($txnId){
						
		$db = Zend_Db_Table::getDefaultAdapter();
			
		 $select = $db->select()											
					  ->from(array('at'=>'applicant_transaction'))
					  ->where("at.at_trans_id= '".$txnId."'");
		
        $rows = $db->fetchRow($select);	
        
        
        $select_program = $db ->select()
		          			  ->from(array('ap'=>$this->_name))					
		                      ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array('IdProgram','ProgramName','ArabicName','ProgramCode'))
		                      ->where("ap.ap_at_trans_id ='".$txnId."'");
								
	    if($rows["at_appl_type"]==1){
			$select_program->where("ap.ap_usm_status  = 1");	
		}
		$program = $db->fetchRow($select_program);			
        
        return array($rows,$program);
		
	}
	
	
	public function getProgramRejectStatus($txn_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ap'=>$this->_name))
					->where("ap.ap_at_trans_id  = '".$txn_id."'")							
					->order("ap.ap_preference Asc");
					
        $stmt = $db->query($select);
        $rows = $stmt->fetchAll();
        
        return $rows;
        
	}
	
}
?>