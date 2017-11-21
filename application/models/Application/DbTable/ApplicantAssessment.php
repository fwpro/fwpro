<?php 

class App_Model_Application_DbTable_ApplicantAssessment extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_assessment';
	protected $_primary = "aar_id";
	
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
	
	
	public function getData($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('arr'=>$this->_name))
					  ->joinLeft(array('asd'=>'applicant_selection_detl'), 'asd.asd_id = arr.aar_rector_selectionid')
					  ->where("arr.aar_trans_id = '".$transaction_id."'")
					  ->order('arr.aar_id desc');
				  
		 $row = $db->fetchRow($select);	
		 return $row;
	}
	
	public function updateAssessmentData($data,$id){
		 $this->update($data, 'aar_trans_id = '. (int)$id);
	}
	
	public function getInfo($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('arr'=>$this->_name))					  
					  ->where("arr.aar_trans_id = '".$transaction_id."'");
									  
		 $row = $db->fetchRow($select);	
		 
		 if($row){
		    return $row;
		 }else{
		 	return null;
		 }
	}
	
	
	public function getSummarySelection($type=null,$intake=null){
		
			$session = new Zend_Session_Namespace('sis');
		
			$db = Zend_Db_Table::getDefaultAdapter();
		
			
		    $select = $db ->select()
						  ->from(array('aar'=>$this->_name))
						  ->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=aar.aar_trans_id')
						  ->join(array('asd'=>'applicant_selection_detl'),'asd.asd_id=aar.aar_dean_selectionid')						 
						  ->where("at.at_selection_status=1")
						  ->where("asd.asd_type=1")
						  ->where("at.at_intake = ?",$intake)		
						  ->order("aar.aar_dean_selectionid asc")
						  ->group("aar.aar_dean_selectionid");
						 // ->group("asd.asd_faculty_id");

					
			if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("asd.asd_faculty_id='".$session->idCollege."'");		
	    	} 
			//echo $select;
									
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
		    return $row;
	}
	
	
	public function getTotalSummarySelection($intake=null,$period=null,$faculty_id,$asd_id,$status){
		
			$session = new Zend_Session_Namespace('sis');
		
			$db = Zend_Db_Table::getDefaultAdapter();
		
			
		    $select = $db->select()
						  ->from(array('aar'=>$this->_name))
						  ->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=aar.aar_trans_id')
						  ->join(array('asd'=>'applicant_selection_detl'),'asd.asd_id=aar.aar_dean_selectionid')						 
						  ->where("at.at_selection_status=1")
						  ->where("asd.asd_type=1")
						  ->where("at.at_intake = ?",$intake)
						 // ->where("at.at_period = ?",$period)
						 // ->where("asd_faculty_id = ?",$faculty_id)
						  ->where("asd_id = ?",$asd_id)
						  ->where("aar.aar_dean_status = ?",$status);

		
			if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("asd.asd_faculty_id='".$session->idCollege."'");		
	    	} 
			
	    	//echo $select.'<br>';
			
	        $row = $db->fetchAll($select);
		    return $row;
	}
	
	public function getPssbDecreeNomor($intake=null){
			
			/*
			 * SELECT at.at_period, asd.asd_nomor
	FROM applicant_assessment AS aar
	LEFT JOIN applicant_selection_detl AS asd ON asd.asd_id = aar.aar_dean_selectionid
	LEFT JOIN applicant_transaction AS at ON at.at_trans_id = aar.aar_trans_id
	WHERE asd.asd_type =1
	GROUP BY at_period, asd_nomor
	ORDER BY `asd`.`asd_id` ASC
			 */
		
			$session = new Zend_Session_Namespace('sis');
		
			$db = Zend_Db_Table::getDefaultAdapter();
		
			
		    $select = $db ->select()
						  ->from(array('aar'=>$this->_name))
						  ->join(array('asd'=>'applicant_selection_detl'),'asd.asd_id=aar.aar_dean_selectionid',array('asd.asd_nomor'))	
						  ->joinLeft(array('at'=>'applicant_transaction'),'at.at_trans_id=aar.aar_trans_id',array('at_intake','at_period'))
						  ->where("asd.asd_type=1")						 
						  ->group("at.at_period")	
						  ->group("asd.asd_nomor")		
						  ->order("at.at_period ASC");
						
			if(isset($intake)){
				 $select->where("at.at_intake = ?",$intake);
			}
					
			if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("asd.asd_faculty_id='".$session->idCollege."'");		
	    	} 
			//echo $select;
									
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
		    return $row;
		
	}
	
	
	public function getTotalAcceptByNomor($intake,$period,$nomor=null){
				
			$db = Zend_Db_Table::getDefaultAdapter();
			        
			$session = new Zend_Session_Namespace('sis');
		
		    $select_nomor = $db ->select()
		  			   		    ->from(array('asd'=>'applicant_selection_detl'),array('asd.asd_id'))
		  			   		    ->where("asd.asd_type = '1'")
		  			   		    ->where("asd.asd_nomor = ?",$nomor);	

		  			   
	        $select = $db ->select()
		  			   ->from(array('at'=>'applicant_transaction'))
		  			   ->joinLeft(array('aar'=>'applicant_assessment'),'aar.aar_trans_id=at.at_trans_id',array())
		  			   ->where("at.at_intake = ? ",$intake)	
		  			   ->where("at.at_period = ? ",$period)	
		  			   ->where("at.at_appl_type = '2'")	
		  			   ->where("at.at_status = 'OFFER'")
		  			   ->where("aar.aar_dean_selectionid IN (?)",$select_nomor)
		  			   ->group("aar.aar_trans_id");
			
	       	$row = $db->fetchAll($select);
		    return count($row);
	}
	
	public function getTotalOffer($ap_id,$nomor,$faculty,$program=null){
		
				$session = new Zend_Session_Namespace('sis');
				
				$db = Zend_Db_Table::getDefaultAdapter();
				
				$bil_applicant = $db ->select()
							  ->from(array('as'=>'applicant_assessment'), array('(as.aar_trans_id)txn_id','asd.asd_nomor'))
							  ->join(array('asd'=>'applicant_selection_detl'), 'asd.asd_id = as.aar_dean_selectionid', array('asd.asd_decree_date'))
							  ->join(array('at'=>'applicant_transaction'),'at.at_trans_id = as.aar_trans_id',array('at.at_intake'))
							  ->join(array('p'=>'tbl_academic_period'),'p.ap_id = at.at_period',array('p.ap_id','p.ap_desc'))
							  ->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
							  ->join(array('pr'=>'tbl_program'), 'pr.ProgramCode = ap.ap_prog_code', array('pr.ProgramCode'))
							  ->join(array('c'=>'tbl_collegemaster'), 'c.IdCollege = pr.IdCollege', array('c.IdCollege','c.ArabicName'))
							  ->where("at.at_period = '".$ap_id."'")							 
							  ->where("asd.asd_nomor = '".$nomor."'")
							  ->where("at.at_status = 'OFFER'")
							  ->where("at.at_appl_type = 2")
							  ->group('as.aar_trans_id');
				
				if(isset($program) && $program!=null){
					$bil_applicant->where("ap.ap_prog_code = '".$program."'");
				}			  
							 
				if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
					$bil_applicant->where("c.IdCollege = '".$session->idCollege."'");		
	    		} else{
	    			$bil_applicant->where("c.IdCollege = '".$faculty."'");
	    		}

	    		//echo $bil_applicant;
	    	
				$row_bil = $db->fetchAll($bil_applicant);
				return count($row_bil);
	}
	
	
	public function getRectorNomorInfo($academic_year,$period,$nomor,$faculty,$decree_date){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					  ->from(array('as'=>'applicant_assessment'), array('as.aar_reg_start_date','as.aar_reg_end_date'))
					  ->join(array('asd'=>'applicant_selection_detl'), 'asd.asd_id = as.aar_rector_selectionid', array('asd.asd_nomor','asd.asd_decree_date'))
					  ->join(array('at'=>'applicant_transaction'),'at.at_trans_id = as.aar_trans_id',array('at.at_academic_year'))
					  //->join(array('p'=>'tbl_academic_period'),'p.ap_id = at.at_period',array('p.ap_id','p.ap_desc'))
					  ->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
					  ->join(array('pr'=>'tbl_program'), 'pr.ProgramCode = ap.ap_prog_code', array('pr.ProgramCode'))
					  ->join(array('c'=>'tbl_collegemaster'), 'c.IdCollege = pr.IdCollege', array('c.IdCollege','c.ArabicName'))
					  ->where("as.aar_reg_start_date is NOT NULL")					 
					  //->group('p.ap_id')
					  ->group('asd.asd_decree_date')
					  ->group('c.IdCollege')
					  ->group('asd.asd_nomor');
					  
		if($academic_year){	
			$select->where("at.at_academic_year = '".$academic_year."'");
		}
		
		if($nomor!=null){	
			$select->where("asd.asd_nomor = '".$nomor."'");
		}
		
		if($faculty!=null){ 
			$select->where("c.IdCollege = '".$faculty."'");		
    	}
    	
		if($decree_date!=null){ 
			$select->where("asd.asd_decree_date = '".$decree_date."'");		
    	}
    	
    	/*if($period!=null){ 
    	 	$select->where("at.at_period = '".$period."'");
    	}*/
    	
    	//echo $select;
    	
        $row = $db->fetchRow($select);
        
        return $row;
	}
	
	
	public function getDeanNomorInfo($academic_year,$period,$faculty,$nomor,$decree_date){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		/*
		 * SELECT distinct(aar_dean_selectionid) 
FROM applicant_assessment `as`
JOIN applicant_transaction as at ON at.at_trans_id = as.aar_trans_id
JOIN applicant_program as ap ON ap.ap_at_trans_id = at.at_trans_id
JOIN tbl_program as pr ON pr.ProgramCode = ap.ap_prog_code
JOIN tbl_collegemaster as c ON c.IdCollege = pr.IdCollege
WHERE aar_rector_selectionid IN(
SELECT asd_id  FROM `applicant_selection_detl` WHERE `asd_nomor` 
LIKE '022/AK.4.02/PSSB-BAA/Usakti/WR.I/V-3/2013')
AND c.IdCollege = '1'
		 */
		
		$select_rector = $db ->select()
							 ->from(array('asd'=>'applicant_selection_detl'), array('asd_id'))
							 ->where('asd.asd_nomor LIKE ?', $nomor);
		
		$select = $db ->select()
						  ->from(array('as'=>'applicant_assessment'),array())
						  ->join(array('asd'=>'applicant_selection_detl'), 'asd.asd_id = as.aar_dean_selectionid', array('DISTINCT(asd.asd_nomor)','asd.asd_decree_date'))
  						  ->join(array('at'=>'applicant_transaction'),'at.at_trans_id = as.aar_trans_id',array())
						  ->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
						  ->join(array('pr'=>'tbl_program'), 'pr.ProgramCode = ap.ap_prog_code',array())
						  ->join(array('c'=>'tbl_collegemaster'), 'c.IdCollege = pr.IdCollege', array('c.IdCollege','c.ArabicName'))
						  ->where("at.at_academic_year = '".$academic_year."'")	
						  ->where("c.IdCollege = '".$faculty."'")
						  ->where("as.aar_rector_selectionid IN (?)",$select_rector)
						  ->order("c.CollegeCode")
						  ->order("asd.asd_decree_date");
						  			    
	    $row = $db->fetchAll($select);
	    //print_r($row);
	  
        return $row;
	}
	
	
	
}	
?>