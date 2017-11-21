<?php 

class App_Model_Application_DbTable_ApplicantTransaction extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_transaction';
	protected $_primary = "at_trans_id";
	
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
	
	
	public function getData($id=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->where("at_status IN ('APPLY','CLOSE','PROCESS')")
					  ->order("at_trans_id desc");
					  
		if($id)	{			
			 $select->where("at_appl_id ='".$id."'");
			 $row = $db->fetchRow($select);				 
		}	 
		
		 return $row;
	}
	
	public function getSearchPaginateFaculty($form, $college_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
					  
		$select = $db->select()
				->from(array('at'=>$this->_name) )
				->joinleft(array('ap'=>'applicant_profile'),'ap.appl_id=at.at_appl_id')
				->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
				->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
				->join(array('apr'=>'applicant_program'), 'at.at_trans_id = apr.ap_at_trans_id')
				->join(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
				->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
				->where('c.IdCollege = ?', $college_id)
				->order('at.at_submit_date desc');
							  

		if( isset($form['intake_id']) && $form['intake_id']!=""){
			$select->where("at.at_intake = '".$form['intake_id']."'");
		
			if(isset($form['period_id']) && $form['period_id']!=""){
				//load prev period
				if($form['load_previous_period']==1){
					$periodDB = new App_Model_Record_DbTable_AcademicPeriod();
					$pData = $periodDB->getData($form['period_id']);
					
					$pList = $periodDB->getPreviousPeriodData($form['intake_id'], $pData['ap_number']);
					
					$plistStr = array();
					$i=0;
					foreach ($pList as $period){
						$plistStr[$i] = $period['ap_id'];
						$i++;	
					}
					
					$select->where('at.at_period in ('.implode(",", $plistStr).')');
				}else{
						$select->where('at.at_period = ?', $form['period_id']);
				}
			}
		}
					  
		if( isset($form['name']) && $form['name']!="" )	{			
			 $select->where("concat(ap.appl_fname,ap.appl_mname,ap.appl_lname) like '%".$form['name']."%'");	 				 
		}
		
		if( isset($form['pes_no']) && $form['pes_no']!="" )	{
			$select->where("at.at_pes_id like '".$form['pes_no']."'");
		}
		
		if( isset($form['application_type']) && $form['application_type']!="" && $form['application_type']!="0" )	{
			$select->where("at.at_appl_type	= '".$form['application_type']."'");
		}
		
		if( isset($form['application_status']) && $form['application_status']!="" && $form['application_status']!="ALL" )	{
			$select->where("at.at_status = '".$form['application_status']."'");
		}
		
		
		
		$row = $db->fetchAll($select);
		
		return $row;
	}
	
	public function getSearchPaginate($form){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('ap'=>'applicant_profile'),'ap.appl_id=at.at_appl_id')
					  ->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
					  ->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
					  ->order("at.at_trans_id desc");

		if( isset($form['intake_id']) && $form['intake_id']!=""){
			$select->where("at.at_intake = '".$form['intake_id']."'");
		
			if(isset($form['period_id']) && $form['period_id']!=""){
				//load prev period
				if($form['load_previous_period']==1){
					$periodDB = new App_Model_Record_DbTable_AcademicPeriod();
					$pData = $periodDB->getData($form['period_id']);
					
					$pList = $periodDB->getPreviousPeriodData($form['intake_id'], $pData['ap_number']);
					
					$plistStr = array();
					$i=0;
					foreach ($pList as $period){
						$plistStr[$i] = $period['ap_id'];
						$i++;	
					}
					
					$select->where('at.at_period in ('.implode(",", $plistStr).')');
				}else{
						$select->where('at.at_period = ?', $form['period_id']);
				}
			}
		}
					  
		if( isset($form['name']) && $form['name']!="" )	{			
			 $select->where("concat(ap.appl_fname,ap.appl_mname,ap.appl_lname) like '%".$form['name']."%'");	 				 
		}
		
		if( isset($form['pes_no']) && $form['pes_no']!="" )	{
			$select->where("at.at_pes_id like '".$form['pes_no']."'");
		}
		
		if( isset($form['application_type']) && $form['application_type']!="" && $form['application_type']!="0" )	{
			$select->where("at.at_appl_type	= '".$form['application_type']."'");
		}
		
		if( isset($form['application_status']) && $form['application_status']!="" && $form['application_status']!="ALL" )	{
			$select->where("at.at_status = '".$form['application_status']."'");
		}
		

		$row = $db->fetchAll($select);
		
		return $row;
	}
	
	
	
	public function getPaginateData(){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		
		$select = $db->select()
			->from(array('at'=>$this->_name) )
			->joinleft(array('ap'=>'applicant_profile'),'ap.appl_id=at.at_appl_id')
			->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
			->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
			->order('at.at_submit_date desc');
		
		
		
		return $select;
	}
	
	public function getPaginateDataFaculty($college_id=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
				->from(array('at'=>$this->_name) )
				->joinleft(array('ap'=>'applicant_profile'),'ap.appl_id=at.at_appl_id')
				->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
				->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
				->join(array('apr'=>'applicant_program'), 'at.at_trans_id = apr.ap_at_trans_id')
				->join(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
				->join(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
				->where('c.IdCollege = ?', $college_id)
				->order('at.at_submit_date desc');
			
		return $select;		
	}
	
	public function getApplicantPaginateData($app_id){
		$app_id = (int)$app_id;
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
				->from($this->_name)
				->where('at_appl_id = '. $app_id)
				->order($this->_primary);
		
		
		return $select;
	}
	
	public function getLastTransaction($applicant_id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->order("at_trans_id desc");
					  
		if($applicant_id!=0)	{			
			 $select->where("at_appl_id ='".$applicant_id."'");
			 $row = $db->fetchRow($select);				 
		}	 
		
		 return $row;
	}
	
	public function getTransactionData($transaction_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->where("at_trans_id = ?", $transaction_id);
					  
		$row = $db->fetchRow($select);
		
		if($row){
			return $row;
		}else{
			return null;
		}
	}
	
	public function getTransaction($transaction_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('at'=>$this->_name) )
					  ->joinleft(array('ap'=>'applicant_profile'),'ap.appl_id=at.at_appl_id')
					  ->joinleft(array('ay'=>'tbl_academic_year'),'ay.ay_id=at.at_academic_year')
					  ->joinleft(array('apd'=>'tbl_academic_period'),'apd.ap_id = at.at_period')
					  ->where("at.at_trans_id = ".$transaction_id);
					  
		$row = $db->fetchRow($select);
		
		if($row){
			return $row;
		}else{
			return null;
		}
	}
	
	public function getApplicantID($admission_type=1){
	
		$db = Zend_Db_Table::getDefaultAdapter();		
		//pr_appl_pesno ( vAdmissionType, OUT vApplicantId)
		$stmt = $db->query("CALL pr_appl_pesno($admission_type,@vApplicantId)");
		
		$select = $db->query("SELECT @vApplicantId as applicantID");	 			
		
		$row = $select->fetchAll();
		return $row[0]["applicantID"]; 
		
	}
	
	public function checkValidApplicant($txnId, $appl_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->where("at_trans_id = ?", $txnId)
					  ->where("at_appl_id = ?", $appl_id);
					  
		$row = $db->fetchRow($select);
		
		if($row){
			return true;
		}else{
			return false;
		}
	}

	public function getSelectionStatus($intake_id,$period_id, $status=null, $faculty_id=null, $program_code=null, $selection_status=null, $prev_period=0, $applType=0){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from( array('at'=>$this->_name) )
					  ->join( array('ap'=>'applicant_profile'), 'ap.appl_id = at.at_appl_id')
					  ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					  ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					  ->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
					  ->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
					  ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					  ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					  ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
					  ->group('at.at_trans_id');

		$select ->where('at.at_pes_id is not null')
				->where('at.at_intake = ?', $intake_id);
				
		//application type
		if($applType!=0){
			$select ->where("at.at_appl_type = ".$applType);
		}		
		
		//load prev period
		if($prev_period!=0){
			$periodDB = new App_Model_Record_DbTable_AcademicPeriod();
			$pData = $periodDB->getData($period_id);
			
			$pList = $periodDB->getPreviousPeriodData($intake_id, $pData['ap_number']);
			
			$plistStr = array();
			$i=0;
			foreach ($pList as $period){
				$plistStr[$i] = $period['ap_id'];
				$i++;	
			}
			
			$select->where('at.at_period in ('.implode(",", $plistStr).')');
		}else{
			$select->where('at.at_period = ?', $period_id);
		}	
				
		if($faculty_id!=null){
			$select->where('c.IdCollege = ?',$faculty_id);	
		}
		
		if($program_code!=null){
			$select->where('apr.ap_prog_code = ?',$program_code);
		}
		
		if($selection_status!=null){
			
			if($selection_status==4){//nak cari offer
				$select->where("at.at_status = 'OFFER'");
				$select->where("at.at_selection_status = 3");
			}elseif($selection_status==5){ //NAK CARI REJECT
				$select->where("at.at_status = 'REJECT'");
				$select->where("at.at_selection_status = 3");
			}else{
				$select->where("at.at_selection_status = $selection_status");
			}
		}
		
		$select ->order('at.at_pes_id');
		//echo $select;
		$row = $db->fetchAll($select);
		
		return $row;
	}
	
/*	public function getSelectionStatus($admission_type=0, $academic_year_id=0, $period_id=0, $college_id=0, $program_code=null, $selection_status=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from( array('at'=>$this->_name) )
					  ->joinLeft( array('ap'=>'applicant_profile'), 'ap.appl_id = at.at_appl_id')
					  ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					  ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					  ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					  ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					  ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'));

		$select ->where('at.at_pes_id is not null');
		
		$select ->order('at.at_pes_id');
		
		$row = $db->fetchAll($select);
		
		return $row;
	}*/
	
	public function getSelectionPSSBTransaction($intake_id,$period_id, $status=null, $faculty_id=null, $program_code=null, $prev_period=0){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from( array('at'=>$this->_name) )
					  ->join( array('ap'=>'applicant_profile'), 'ap.appl_id = at.at_appl_id')
					  ->join(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					  ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					  ->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
					  ->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
					  //->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					  ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_transaction_id=at.at_trans_id ')
					  ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					  ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'));

		$select ->where('at.at_pes_id is not null')
				->where('at.at_intake = ?', $intake_id)
				->where("at.at_selection_status = 0")
				->where("at.at_appl_type = 2");
				
		//load prev period
		if($prev_period!=0){
			$periodDB = new App_Model_Record_DbTable_AcademicPeriod();
			$pData = $periodDB->getData($period_id);
			
			$pList = $periodDB->getPreviousPeriodData($intake_id, $pData['ap_number']);
			
			$plistStr = array();
			$i=0;
			foreach ($pList as $period){
				$plistStr[$i] = $period['ap_id'];
				$i++;	
			}
			
			$select->where('at.at_period in ('.implode(",", $plistStr).')');
		}else{
			$select->where('at.at_period = ?', $period_id);
		}		
		
		if($faculty_id!=null){
			$select->where('c.IdCollege = ?',$faculty_id);	
		}
		
		if($program_code!=null){
			$select->where('apr.ap_prog_code = ?',$program_code);
		}
		
		if($status!=null){
			$select->where('at.at_status = ?',$status);
		}
		
		$select ->order('at.at_pes_id');
		
		$row = $db->fetchAll($select);
		
		//check for education using appl_id
		$applicantEducationDb = new App_Model_Application_DbTable_ApplicantEducation();
		
		foreach ($row as $key=>$data){
			
			if(!isset($data['ae_id'])){
				//get education from appl id
				
				$educationData = $applicantEducationDb->getDataByapplID($data['at_appl_id']);
				
				$row[$key]['ae_id'] = $educationData['ae_id'];
				$row[$key]['ae_appl_id'] = $educationData['ae_appl_id']; 
	            $row[$key]['ae_transaction_id'] = $educationData['ae_transaction_id']; 
	            $row[$key]['ae_institution'] =$educationData['ae_institution']; 
	            $row[$key]['ae_discipline_code'] =$educationData['ae_discipline_code']; 
	            $row[$key]['ae_year_from'] =$educationData['ae_year_from']; 
	            $row[$key]['ae_year_end'] =$educationData['ae_year_end']; 
	            $row[$key]['ae_year'] =$educationData['ae_year']; 
	            $row[$key]['ae_award'] = $educationData['ae_award']; 
			}
			
		}
		
		
		return $row;
	}
	
	public function getRectorSelectionPSSBTransaction($intake_id,$period_id, $status=null, $faculty_id=null, $program_code=null, $prev_period=0){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from( array('at'=>$this->_name) )
					  ->join( array('ap'=>'applicant_profile'), 'ap.appl_id = at.at_appl_id')
					  ->join(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					  ->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake = at.at_intake')
					  ->joinLeft(array('aprd'=>'tbl_academic_period'),'aprd.ap_id = at.at_period')
					  ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					  //->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					  ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_transaction_id=at.at_trans_id ')
					  ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					  ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'));

		$select ->where('at.at_pes_id is not null')
				->where('at.at_intake = ?', $intake_id)
				->where("at.at_selection_status = 1")
				->where("at.at_appl_type = 2");
		
		//load prev period
		if($prev_period!=0){
			$periodDB = new App_Model_Record_DbTable_AcademicPeriod();
			$pData = $periodDB->getData($period_id);
			
			$pList = $periodDB->getPreviousPeriodData($intake_id, $pData['ap_number']);
			
			$plistStr = array();
			$i=0;
			foreach ($pList as $period){
				$plistStr[$i] = $period['ap_id'];
				$i++;	
			}
			
			$select->where('at.at_period in ('.implode(",", $plistStr).')');
		}else{
			$select->where('at.at_period = ?', $period_id);
		}
		
		if($faculty_id!=null){
			$select->where('c.IdCollege = ?',$faculty_id);	
		}
		
		if($program_code!=null){
			$select->where('apr.ap_prog_code = ?',$program_code);
		}
		
		if($status!=null){
			$select->where('at.at_status = ?',$status);
		}
		
		$select ->order('at.at_pes_id');
		
		
		$row = $db->fetchAll($select);
		
		//check for education using appl_id
		$applicantEducationDb = new App_Model_Application_DbTable_ApplicantEducation();
		
		foreach ($row as $key=>$data){
			
			if(!isset($data['ae_id'])){
				//get education from appl id
				
				$educationData = $applicantEducationDb->getDataByapplID($data['at_appl_id']);
				
				$row[$key]['ae_id'] = $educationData['ae_id'];
				$row[$key]['ae_appl_id'] = $educationData['ae_appl_id']; 
	            $row[$key]['ae_transaction_id'] = $educationData['ae_transaction_id']; 
	            $row[$key]['ae_institution'] =$educationData['ae_institution']; 
	            $row[$key]['ae_discipline_code'] =$educationData['ae_discipline_code']; 
	            $row[$key]['ae_year_from'] =$educationData['ae_year_from']; 
	            $row[$key]['ae_year_end'] =$educationData['ae_year_end']; 
	            $row[$key]['ae_year'] =$educationData['ae_year']; 
	            $row[$key]['ae_award'] = $educationData['ae_award']; 
			}
			
		}
		
		return $row;
	}
	
	
	public function getResultSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$session = new Zend_Session_Namespace('sis');
			
		 $select = $db ->select()
					   ->from(array('at'=>$this->_name))
					   ->joinleft(array('ap'=>'applicant_profile'),'at.at_appl_id=ap.appl_id')
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
					   ->where("at.at_selection_status = 3")
					   ->order("p.ProgramCode asc")
					   ->order("at.at_pes_id asc");
					   
					   
					   if($condition!=null){
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}		
					   		if(isset($condition["academic_year"]) && $condition["academic_year"]!=''){
					   			$select->where("at.at_academic_year ='".$condition["academic_year"]."'");
					  		}					  		
					  		if(isset($condition["status"]) && $condition["status"]!=''){
								$select->where("at.at_status  = '".$condition["status"]."'");
					  		}
					  		if(isset($condition["period"]) && $condition["period"]!=''){	
					   			$select->where("at_period = '".$condition["period"]."'");
					  		}
					   }
					   
		if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("c.IdCollege='".$session->idCollege."'");		
	    } else{
	   			 if(isset($condition["faculty"]) && $condition["faculty"]!=''){	
					   $select->where("c.IdCollege = '".$condition["faculty"]."'");
				  }
	    }  			   
			
		$row = $db->fetchAll($select);	  
		return $row;
	}
	
	
	public function getStatusSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$session = new Zend_Session_Namespace('sis');
			
		 $select = $db ->select()
					   ->from(array('at'=>$this->_name))
					   ->joinleft(array('ap'=>'applicant_profile'),'at.at_appl_id=ap.appl_id')
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
					   //->where("at.at_selection_status = 3")
					   ->order("p.ProgramCode asc")
					   ->order("at.at_pes_id asc");
					   
					   
					   if($condition!=null){
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}		
					   		if(isset($condition["academic_year"]) && $condition["academic_year"]!=''){
					   			$select->where("at.at_academic_year ='".$condition["academic_year"]."'");
					  		}					  		
					  		if(isset($condition["status"]) && $condition["status"]!=''){
								$select->where("at.at_status  = '".$condition["status"]."'");
					  		}
					  		if(isset($condition["period"]) && $condition["period"]!=''){	
					   			$select->where("at_period = '".$condition["period"]."'");
					  		}
					   }
					   
		
		if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("c.IdCollege='".$session->idCollege."'");		
	    }else{
	   			 if(isset($condition["faculty"]) && $condition["faculty"]!=''){	
					   $select->where("c.IdCollege = '".$condition["faculty"]."'");
				  }
	    }  				   
		//echo $select; 			   
			
		$row = $db->fetchAll($select);	  
		return $row;
	}
	
	
public function getTotalApplicant($academic_year,$admission_type,$program_code=0,$status=null,$preference=null,$data=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')					 
					  ->where("at.at_appl_type ='".$admission_type."'")
					  ->where("apr.ap_prog_code ='".$program_code."'");
					  
					  if(isset($academic_year) && $academic_year!=''){
		             	 $select->where("at.at_academic_year  = '".$academic_year."'");
					  }
					  
					  if(isset($status)){	

					  	if($status=="USMPAID"){
					  		$select->where("at.at_status  != 'APPLY' AND at.at_status  != 'CLOSE' ");
					  	}else{
					  		$select->where("at.at_status  = '".$status."'");
					  	}
					  }
					  
	 				  if(isset($preference) && $preference!=''){
		             	 $select->where("apr.ap_preference = '".$preference."'");
					  }
					  
					  if(isset($data["quit"]) && $data["quit"]!=''){
		             	 $select->where("at.at_quit_status = '".$data["quit"]."'");
					  }
					  
					  if(isset($data["move"]) && $data["move"]!=''){
		             	 $select->where("at.at_move_status = '".$data["move"]."'");
					  }
		
		$row = $db->fetchAll($select);
			  
		if(isset($data["paid"]) && $data["paid"]==true){
			foreach ($row as $key=>$applicant){
				$select = $db ->select()
						->from(array('pm'=>'payment_main'))
						->join(array('pi'=>'applicant_proforma_invoice'),'pm.billing_no = pi.billing_no')
					  	->where("pi.payee_id ='".$applicant['at_pes_id']."'");
					  	
				$row2 = $db->fetchRow($select);	  	
				
				if(!$row2){
					unset($row[$key]);
				}
			}
		}
		
		//echo $select;
		
		return count($row);
	}
	
	public function getTotalOfferUsmApplicant($academic_year,$admission_type,$program_code=0,$status=null,$preference=null, $paid=false){
		
		$db = Zend_Db_Table::getDefaultAdapter();
			
		
		$select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					  ->where("at.at_appl_type ='".$admission_type."'")
					  ->where("apr.ap_prog_code ='".$program_code."'")
					  ->where("apr.ap_usm_status = 1 ");
					  
					  if(isset($status)){
		             	 $select->where("at.at_status  = '".$status."'");
					  }
					  
	 				  if(isset($preference)){
		             	 $select->where("apr.ap_preference = '".$preference."'");
					  }
					  
					   if(isset($academic_year) && $academic_year!=''){
		             	 $select->where("at.at_academic_year  = '".$academic_year."'");
					  }
					  
		
		$row = $db->fetchAll($select);	  
		
		
		if($paid==true){
			
			if($row!=null){
				foreach ($row as $key=>$applicant){
					$select = $db ->select()
							->from(array('pm'=>'payment_main'))
							->join(array('pi'=>'applicant_proforma_invoice'),'pm.billing_no = pi.billing_no')
						  	->where("pi.payee_id ='".$applicant['at_pes_id']."'");
						  	
					$row2 = $db->fetchRow($select);	  	
					
					if(!$row2){
						unset($row[$key]);
					}
				}
			}
			
			
		}
		
		return count($row);
		
	}
	
	public function getTotalRejectUsmApplicant($academic_year,$admission_type,$program_code=0,$status=null,$preference=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
			
		//kene kira 2
		
		$select_offer_tapi_reject_program = $db ->select()
												  ->from(array('at'=>$this->_name))
												  ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
												  ->where("at.at_academic_year  = '".$academic_year."'")
												  ->where("at.at_appl_type ='".$admission_type."'")
												  ->where("apr.ap_prog_code ='".$program_code."'")
												  ->where("apr.ap_preference = '".$preference."'")
												  ->where("apr.ap_usm_status = 2 OR apr.ap_usm_status = 0")
												  ->where("at.at_status  = 'OFFER'");
		$row_offer = $db->fetchAll($select_offer_tapi_reject_program);	
					  
		$select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					  ->where("at.at_appl_type ='".$admission_type."'")
					  ->where("apr.ap_prog_code ='".$program_code."'")
					  ->where("apr.ap_usm_status != 1 ");
					  
					  if(isset($status)){
		             	 $select->where("at.at_status  = '".$status."'");
					  }
					  
	 				  if(isset($preference)){
		             	 $select->where("apr.ap_preference = '".$preference."'");
					  }
					  
					   if(isset($academic_year) && $academic_year!=''){
		             	 $select->where("at.at_academic_year  = '".$academic_year."'");
					  }
					  
		
		$row = $db->fetchAll($select);	  
		
		$jumlah = count($row) + count($row_offer);
		
		return $jumlah;
	}
	
	/*
	 * For Surat Keputusan Wakil Rector
	 */
	public function getApplicantByNomor($asd_id=null,$intake=null,$status=null){
		
			
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('at'=>$this->_name))	
					  ->joinLeft(array('ap'=>'applicant_profile'), 'ap.appl_id = at.at_appl_id')					
					  ->JOINinner(array('aar'=>'applicant_assessment'), 'aar.aar_trans_id = at.at_trans_id')
					  ->joinLeft(array('asd'=>'applicant_selection_detl'), 'asd.asd_id = aar.aar_rector_selectionid')
					  ->where("at.at_selection_status = '3'")
					  ->where("asd.asd_type = '2'");
			
		 if($asd_id){
		 	$select->where("asd.asd_id = '".$asd_id."'");
		 }
		 
		 if($intake){
		 	$select->where("at.at_intake = '".$intake."'");
		 }
		 
		/*if($period){
		 	$select->where("at.at_period = '".$period."'");
		 }*/
		 
		if($status){
		 	$select->where("at.at_status = '".$status."'");
		 }
		 
		// echo $select;
		 
		 $row = $db->fetchAll($select);	
		 return $row;
	}
	
	
	public function getDeanApplicantByNomor($asd_id=null,$intake=null,$status=null){
			
		
		$session = new Zend_Session_Namespace('sis');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
					  ->from(array('at'=>$this->_name))	
					  ->joinLeft(array('ap'=>'applicant_profile'), 'ap.appl_id = at.at_appl_id')					
					  ->JOINinner(array('aar'=>'applicant_assessment'), 'aar.aar_trans_id = at.at_trans_id')
					  ->joinLeft(array('asd'=>'applicant_selection_detl'), 'asd.asd_id = aar.aar_dean_selectionid')
					  ->where("at.at_selection_status != '0'")
					  ->where("asd.asd_type = '1'");
			
		 if($asd_id){
		 	$select->where("asd.asd_id = '".$asd_id."'");
		 }
		 
		 if($intake){
		 	$select->where("at.at_intake = '".$intake."'");
		 }
		 
		 if($status){
		 	$select->where("aar.aar_dean_status = '".$status."'");
		 }
		 
		
		if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("asd.asd_faculty_id='".$session->idCollege."'");		
	    }
		//  if($asd_id=='64'){ echo $select;}
		  
		 $row = $db->fetchAll($select);	
		 return $row;
	}
	
	
	
	public function getListApplicant($condition=null){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('ap'=>'applicant_profile'),'at.at_appl_id=ap.appl_id')
					  ->where("at.at_status != 'REJECT' OR at.at_status != 'OFFER' OR at.at_status != 'REGISTER'" )
					  ->order("at.at_create_date DESC");
					  
					  
	  					if($condition!=null){					   		
	  						if(isset($condition["entry_type"]) && $condition["entry_type"]!=''){
					   			$select->where("at.entry_type ='".$condition["entry_type"]."'");
					   		}
					   		
					   }			 
						
		return $row = $db->fetchAll($select);	
	}
	
	public function getListApplicantPaginateData($condition=null){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('ap'=>'applicant_profile'),'at.at_appl_id=ap.appl_id')
					  ->where("at.at_status = 'PROCESS'")
					  ->orwhere ("at.at_status = 'CLOSE'" )
					  ->order("at.at_create_date DESC");
				  
	  					if($condition!=null){					   		
	  						if(isset($condition["entry_type"]) && $condition["entry_type"]!=''){
					   			$select->where("at.entry_type ='".$condition["entry_type"]."'");
					   		}
					   }	
						
		return $select;
	}
	
	
	public function getUSMApplicant($ptest_code,$preference,$intake,$period,$selection_status,$load_previous_period,$program,$aps_id,$aps_test_date){
		
				
		 $db = Zend_Db_Table::getDefaultAdapter();
		 
		 //get placement schedule id
		 if(!$aps_id){
		 	
			 $select_schedule = $db ->select()
			 				        ->from(array('aps'=>'appl_placement_schedule'),array('aps_id'))
			 				        ->where("aps.aps_placement_code ='".$ptest_code."'");
			 				        
									if(isset($aps_test_date) && $aps_test_date!=''){
					   					$select_schedule->where("aps.aps_test_date='".$aps_test_date."'");
					   				}
					   				
				   				
		 } //end 

		 $select = $db ->select()
					  ->from(array('at'=>$this->_name))
					  ->joinleft(array('ap'=>'applicant_program'),'ap.ap_at_trans_id=at.at_trans_id')
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')					  	
					  ->where("at.at_appl_type=1")
					  ->where("at.at_status='PROCESS'")
					  ->where("at.at_selection_status = 0")
					  ->where("apt.apt_usm_attendance=1");
					  
						
						if(isset($aps_id) && $aps_id!=''){
				   			$select->where("apt.apt_aps_id ='".$aps_id."'");
				   		}else{
				   			$select->where("apt.apt_aps_id IN (?)",$select_schedule);
				   		}
						if(isset($preference) && $preference!=''){
				   			$select->where("ap.ap_preference ='".$preference."'");
				   		}
						if(isset($intake) && $intake!=''){
				   			$select->where("at.at_intake ='".$intake."'");
				   		}
				   		if(isset($period) && $period!=''){
				   			$select->where("at.at_period ='".$period."'");
				   		}
						if(isset($load_previous_period) && $load_previous_period!=''){
				   			$select->where("at.at_period ='".$load_previous_period."'");
				   		}		
				   		if(isset($program) && $program!=''){
				   			$select->where("ap.ap_prog_code ='".$program."'");
				   		}			 
		//echo $select;			
		return $row = $db->fetchAll($select);	
	}
	
	
	public function getApplicantPassUSM($ptest_code,$preference,$intake,$period,$selection_status,$load_previous_period,$program,$aps_id,$aps_test_date,$limit=null,$passmark=0){
		
				
		 $db = Zend_Db_Table::getDefaultAdapter();
		 
		 
		 if($passmark==0){
		 
		 	//get placement pass mark for particular program
		 	$select_ptest = $db ->select()
		 				 	->from(array('app'=>'appl_placement_program'))
		 				 	->where("app.app_program_code = ?",$program)
		 				 	->where("app.app_placement_code = ?",$ptest_code);
		 				
		  	$row = $db->fetchRow($select_ptest);		 
		  	$program_pass_mark = $row["app_pass_mark"];
		 }else{
				 $program_pass_mark=$passmark;
		 }
		  
		 if($program_pass_mark){	
		  		  	
						 //get placement schedule id
						 if(!$aps_id){
						 	
							 $select_schedule = $db ->select()
							 				        ->from(array('aps'=>'appl_placement_schedule'),array('aps_id'))
							 				        ->where("aps.aps_placement_code ='".$ptest_code."'");
							 				        
													if(isset($aps_test_date) && $aps_test_date!=''){
									   					$select_schedule->where("aps.aps_test_date='".$aps_test_date."'");
									   				}
									   				
								   				
						 } //end 
				
						 
						 $select = $db ->select()
									  ->from(array('at'=>$this->_name))
									  ->join(array('ap'=>'applicant_program'),'ap.ap_at_trans_id=at.at_trans_id')
									  ->join(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')					  	
									  ->where("at.at_appl_type=1")
									  ->where("at.at_status='PROCESS'")
									  ->where("apt.apt_usm_attendance=1")
									  ->where("at.at_selection_status = 0")
									  ->where("ap.ap_usm_mark >= ?",$program_pass_mark)									 
									  ->order("ap.ap_usm_mark DESC");
									  
										
										if(isset($aps_id) && $aps_id!=''){
								   			$select->where("apt.apt_aps_id ='".$aps_id."'");
								   		}else{
								   			$select->where("apt.apt_aps_id IN (?)",$select_schedule);
								   		}
										if(isset($preference) && $preference!=''){
								   			$select->where("ap.ap_preference ='".$preference."'");
								   		}
										if(isset($intake) && $intake!=''){
								   			$select->where("at.at_intake ='".$intake."'");
								   		}
								   		if(isset($period) && $period!=''){
								   			$select->where("at.at_period ='".$period."'");
								   		}
										if(isset($load_previous_period) && $load_previous_period!=''){
								   			$select->where("at.at_period ='".$load_previous_period."'");
								   		}					  			 				
								   		if(isset($program) && $program!=''){
								   			$select->where("ap.ap_prog_code ='".$program."'");
								   		}	
								   		if(isset($limit) && $limit!=0){
				   							$select->limit($limit.", 0");
				   						}			 
						//echo $select;			
						return $row = $db->fetchAll($select);	
		 }//end 
	}
	
	
	public function getApplicantFailUSM($ptest_code,$preference,$intake,$period,$selection_status,$load_previous_period,$program,$aps_id,$aps_test_date){
		
				
		 $db = Zend_Db_Table::getDefaultAdapter();
		 
		 //get placement pass mark for particular program
		 $select_ptest = $db ->select()
		 				 	->from(array('app'=>'appl_placement_program'))
		 				 	->where("app.app_program_code = ?",$program)
		 				 	->where("app.app_placement_code = ?",$ptest_code);
		 				
		  $row = $db->fetchRow($select_ptest);		 
		  $program_pass_mark = $row["app_pass_mark"];
		  
		  
		 if($program_pass_mark){	
		  		  	
						 //get placement schedule id
						 if(!$aps_id){
						 	
							 $select_schedule = $db ->select()
							 				        ->from(array('aps'=>'appl_placement_schedule'),array('aps_id'))
							 				        ->where("aps.aps_placement_code ='".$ptest_code."'");
							 				        
													if(isset($aps_test_date) && $aps_test_date!=''){
									   					$select_schedule->where("aps.aps_test_date='".$aps_test_date."'");
									   				}
									   				
								   				
						 } //end 
				
						 
						 $select = $db ->select()
									  ->from(array('at'=>$this->_name))
									  ->joinleft(array('ap'=>'applicant_program'),'ap.ap_at_trans_id=at.at_trans_id')
									  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')					  	
									  ->where("at.at_appl_type=1")
									  ->where("at.at_status='PROCESS'")
									  ->where("apt.apt_usm_attendance=1")
									  ->where("at.at_selection_status = 0")
									  ->where("ap.ap_usm_mark < ?",$program_pass_mark)
									  ->order("ap.ap_usm_mark DESC");
									  
										
										if(isset($aps_id) && $aps_id!=''){
								   			$select->where("apt.apt_aps_id ='".$aps_id."'");
								   		}else{
								   			$select->where("apt.apt_aps_id IN (?)",$select_schedule);
								   		}
										if(isset($preference) && $preference!=''){
								   			$select->where("ap.ap_preference ='".$preference."'");
								   		}
										if(isset($intake) && $intake!=''){
								   			$select->where("at.at_intake ='".$intake."'");
								   		}
								   		if(isset($period) && $period!=''){
								   			$select->where("at.at_period ='".$period."'");
								   		}
										if(isset($load_previous_period) && $load_previous_period!=''){
								   			$select->where("at.at_period ='".$load_previous_period."'");
								   		}	
								   		if(isset($program) && $program!=''){
								   			$select->where("ap.ap_prog_code ='".$program."'");
								   		}	

								   		if(isset($limit) && $limit!=0){
				   							$select->limit($limit.", 0");
				   						}	
						//echo $select;			
						return $row = $db->fetchAll($select);	
		 }//end 
	}
	
	
	public function getApplicantInPool($program,$preference=null,$faculty=null){
		
		 $db = Zend_Db_Table::getDefaultAdapter();
		
		 $select = $db ->select()
		 				 	->from(array('at'=>$this->_name))
		 				 	->join(array('ats'=>'applicant_temp_usm_selection'),'at.at_trans_id = ats.ats_transaction_id')
		 				 	->joinleft(array('ap'=>'applicant_program'),'ap.ap_id = ats.ats_ap_id')	
		 				 	->order("ap.ap_prog_code")	 				 	 				 	
		 				 	->order("ap.ap_usm_mark desc");
		 				 	
		 				 	if($preference){
		 				 		$select->where("ats.ats_preference = ?",$preference);
		 				 	}
		 				 	
		 				 	if(isset($program) && ($program!='')){
		 				 		$select->where("ats.ats_program_code = ?",$program);
		 				 		
		 				 	}elseif(isset($faculty) && ($faculty!='')){
		 				 		$select->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ats.ats_program_code',array());
		 				 		$select->where("p.IdCollege = ?",$faculty);		 				 		
		 				 	}
		 				 			
		  //echo $select;			 	
		  $row = $db->fetchAll($select);	
		  return $row;	 
	}
	
	
public function getUSMRectorVerification($intake,$program,$preference=null,$faculty=null){
		
				
		 $db = Zend_Db_Table::getDefaultAdapter();
		
		 $select = $db ->select()
		 				 	->from(array('at'=>$this->_name),array('at_appl_id','at.at_trans_id'))
		 				 	->join(array('aau'=>'applicant_assessment_usm'),'aau.aau_trans_id=at.at_trans_id',array('aau.aau_ap_id','aau.aau_id'))
		 				 	->joinleft(array('ap'=>'applicant_program'),'ap.ap_id = aau.aau_ap_id')
		 				 	->where("at.at_selection_status = 1")
		 				 	->where("aau.aau_reversal_status != 1")
		 				 	->where("at.at_intake = ?",$intake)
		 				 	//->order("ap.ap_prog_code")
		 				 	->order("ap.ap_usm_mark desc");
		 				 	
		 				 	
		 				 	if($preference){
		 				 		$select->where("ap.ap_preference = ?",$preference);
		 				 	}
		 				 	
							if(isset($program) && ($program!='')){
		 				 		$select->where("ap.ap_prog_code = ?",$program);
		 				 		
		 				 	}elseif(isset($faculty) && ($faculty!='')){
		 				 		$select->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ap.ap_prog_code',array());
		 				 		$select->where("p.IdCollege = ?",$faculty);
		 				 		
		 				 	}
		  //echo $select;				 	
		  $row = $db->fetchAll($select);	
		  return $row;
	}
	
	public function getUSMSelectionStatus($intake=null,$period=null,$program=null,$preference=null,$status=null,$ptest_code=null,$aps_test_date=null,$aps_id=null){
				
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		if(!$aps_id){
						 	
			 $select_schedule = $db ->select()
			 				        ->from(array('aps'=>'appl_placement_schedule'),array('aps_id'))
			 				        ->where("aps.aps_placement_code ='".$ptest_code."'");
			 				        
									if(isset($aps_test_date) && $aps_test_date!=''){
					   					$select_schedule->where("aps.aps_test_date='".$aps_test_date."'");
					   				}				   				
		 } //end 
		 
		 
		
		
		 $select = $db ->select()
		 				 	->from(array('at'=>$this->_name))		 				 
		 				 	->join(array('ap'=>'applicant_program'),'at.at_trans_id =ap.ap_at_trans_id')
		 				 	->joinLeft(array('apf'=>'applicant_profile'),'apf.appl_id=at.at_appl_id',array('appl_fname','appl_mname','appl_lname'))
							->joinLeft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')	
		 				 	->where("at.at_appl_type=1")
		 				 	->where("at.at_status!='APPLY'")
		 				 	->where("at.at_status!='CLOSE'")		 				 	
		 				 	->order("at.at_trans_id ASC")
		 				 	->group("at.at_trans_id");		 				

							if(isset($aps_id) && $aps_id!=''){								
								 $select->where("apt.apt_aps_id ='".$aps_id."'");
							}else{									
								 $select->where("apt.apt_aps_id IN (?)",$select_schedule);
							}
							
							if(isset($intake) && $intake!=''){
								   $select->where("at.at_intake ='".$intake."'");
							}
							if(isset($period) && $period!=''){
								   $select->where("at.at_period ='".$period."'");
							}
							if(isset($program) && $program!=''){
								   $select->where("ap.ap_prog_code ='".$program."'");
							}
							if(isset($preference) && $preference!=''){
								   $select->where("ap.ap_preference ='".$preference."'");
							}
							if(isset($status) && $status!=''){
								
								if($status==3){
									$select->where("at.at_status ='OFFER'");									
								}elseif($status==5){
									$select->where("at.at_status ='REJECT'");
								}else{
								   $select->where("at.at_selection_status ='".$status."'");
								}
							}
		 // echo $select;					   		
		  $row = $db->fetchAll($select);	
		  return $row;
	}

	
	function getPassingMark($program,$ptest_code){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select_ptest = $db ->select()
			 				 	->from(array('app'=>'appl_placement_program'))
			 				 	->where("app.app_program_code = ?",$program)
			 				 	->where("app.app_placement_code = ?",$ptest_code);
			 				
			  $row = $db->fetchRow($select_ptest);		 
			  $program_pass_mark = $row["app_pass_mark"];
			  return $program_pass_mark;
	}


public function getApplicantPassNotInPoolUSM($ptest_code,$preference,$intake,$period,$selection_status,$load_previous_period,$program,$aps_id,$aps_test_date,$limit=null,$passmark=null,$faculty=null,$attendance=null){
		
				
		 $db = Zend_Db_Table::getDefaultAdapter();
		 
		 
		 if($passmark==null){
		 
		 	//get placement pass mark for particular program
		 	$select_ptest = $db ->select()
		 				 	->from(array('app'=>'appl_placement_program'))
		 				 	->where("app.app_program_code = ?",$program)
		 				 	->where("app.app_placement_code = ?",$ptest_code);
		 				
		  	$row = $db->fetchRow($select_ptest);		 
		  	$program_pass_mark = $row["app_pass_mark"];
		 }else{
				 $program_pass_mark=$passmark;
		 }
		  
		// if($program_pass_mark){	
		 	
		 	
		 	
		 				//get list in pool
		 				$select_pool = $db ->select()
		 				 				->from(array('ats'=>'applicant_temp_usm_selection'),array('ats.ats_transaction_id'))
		 				 				->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=ats.ats_program_code',array())
		 				 				->order("ats.ats_program_code");

		 				 				if(isset($program) && ($program!='')){
		 				 					$select_pool->where("ats.ats_program_code = ?",$program);
		 				 					
		 				 				}elseif(isset($faculty) && ($faculty!='')){
		 				 					$select_pool->where("p.IdCollege = ?",$faculty);
		 				 				}
		 				 
		 				 	
		  					
		  		  	
						 //get placement schedule id
						 if(!$aps_id){
						 	
							 $select_schedule = $db ->select()
							 				        ->from(array('aps'=>'appl_placement_schedule'),array('aps_id'))
							 				        ->where("aps.aps_placement_code ='".$ptest_code."'");
							 				        
													if(isset($aps_test_date) && $aps_test_date!=''){
									   					$select_schedule->where("aps.aps_test_date='".$aps_test_date."'");
									   				}
									   				
								   				
						 } //end 
				
						 
						 $select = $db ->select()
									  ->from(array('at'=>$this->_name))
									  ->join(array('ap'=>'applicant_program'),'ap.ap_at_trans_id=at.at_trans_id')
									  ->join(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')					  	
									  ->where("at.at_appl_type=1")
									  ->where("at.at_status='PROCESS'")
									  //->where("apt.apt_usm_attendance=1")
									  ->where("at.at_selection_status = 0")
									  ->where("ap.ap_usm_mark >= '".$program_pass_mark."'")	
									  ->where("at.at_trans_id NOT IN (?)",$select_pool)
									  ->order("ap.ap_prog_code")								 
									  ->order("ap.ap_usm_mark DESC");
									
									  
										
										if(isset($aps_id) && $aps_id!=''){
								   			$select->where("apt.apt_aps_id ='".$aps_id."'");
								   		}else{
								   			$select->where("apt.apt_aps_id IN (?)",$select_schedule);
								   		}
										if(isset($preference) && $preference!=''){
								   			$select->where("ap.ap_preference ='".$preference."'");
								   		}
										if(isset($intake) && $intake!=''){
								   			$select->where("at.at_intake ='".$intake."'");
								   		}
								   		if(isset($period) && $period!=''){
								   			$select->where("at.at_period ='".$period."'");
								   		}
										if(isset($load_previous_period) && $load_previous_period!=''){
								   			$select->where("at.at_period ='".$load_previous_period."'");
								   		}					  			 				
								   		if(isset($program) && $program!=''){
								   			$select->where("ap.ap_prog_code ='".$program."'");
								   		}
										if(isset($attendance)){											
											if($attendance==1){
				   								$select->where("apt.apt_usm_attendance=1");//hadir
											}elseif($attendance==2){
												$select->where("apt.apt_usm_attendance='0'");//tak hadir
											}
				   						}	
								   		if(isset($limit) && $limit!=0){
				   							$select->limit($limit.", 0");
				   						}
				   							
				   	
						return $row = $db->fetchAll($select);	
		 //}//end 
	}
	
public function getTotalReject($ap_id,$nomor,$faculty,$program=null){
		
				$session = new Zend_Session_Namespace('sis');
				
				$db = Zend_Db_Table::getDefaultAdapter();
				
				$bil_applicant = $db ->select()
							  ->from(array('at'=>'applicant_transaction'),array('at.at_intake'))
							  ->join(array('p'=>'tbl_academic_period'),'p.ap_id = at.at_period',array('p.ap_id','p.ap_desc'))
							  ->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
							  ->join(array('pr'=>'tbl_program'), 'pr.ProgramCode = ap.ap_prog_code', array('pr.ProgramCode'))
							  ->join(array('c'=>'tbl_collegemaster'), 'c.IdCollege = pr.IdCollege', array('c.IdCollege','c.ArabicName'))
							  ->where("at.at_period = '".$ap_id."'")	
							  //->where("asd.asd_nomor = '".$data['asd_nomor']."'")
							  ->where("at.at_status in ('REJECT')")
							  ->where("at.at_appl_type = 2")
							  ->group('at.at_intake');
				
				if(isset($program) && $program!=null){
					$bil_applicant->where("ap.ap_prog_code = '".$program."'");
				}	
						    				
				if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
					$bil_applicant->where("c.IdCollege = '".$session->idCollege."'");		
	    		} else{
	    			$bil_applicant->where("c.IdCollege = '".$faculty."'");
	    		}
	    				    
				$row_bil = $db->fetchAll($bil_applicant);
				return count($row_bil);
	}
	
	
public function getTotalTransaction($academic_year,$appl_type,$program=null,$preference=null){	
				
				$db = Zend_Db_Table::getDefaultAdapter();
				
				$bil_applicant = $db ->select()
								  ->from(array('at'=>'applicant_transaction'),array('at.at_trans_id'))
								  ->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
							  	  ->join(array('pr'=>'tbl_program'), 'pr.ProgramCode = ap.ap_prog_code', array())							  	 
								  ->where("at.at_status != 'APPLY'")
								  ->where("at.at_appl_type = '".$appl_type."'");

				if(isset($program) && $program!=null){
					$bil_applicant->where("ap.ap_prog_code = '".$program."'");
				}
				if(isset($preference) && $preference!=''){
					$bil_applicant->where("ap.ap_preference ='".$preference."'");
				}
 				if(isset($academic_year) && $academic_year!=''){
		             	 $bil_applicant->where("at.at_academic_year  = '".$academic_year."'");
					  }
	    		//echo $bil_applicant;		    
				$row_bil = $db->fetchAll($bil_applicant);
				return count($row_bil);
	}
	
	public function getTotalApplication($academic_year,$appl_type,$program=null,$preference=null){
		
				$db = Zend_Db_Table::getDefaultAdapter();
				
				$bil_applicant = $db ->select()				
								  ->from(array('af'=>'applicant_profile'),array('af.appl_id'))
								  ->join(array('at'=>'applicant_transaction'), 'at.at_appl_id=af.appl_id', array('at.at_trans_id'))
								  ->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
							  	  ->join(array('pr'=>'tbl_program'), 'pr.ProgramCode = ap.ap_prog_code', array())							  	 
								  ->where("at.at_status != 'APPLY'")
								  ->where("at.at_appl_type = '".$appl_type."'")
								  ->group("af.appl_id");

				if(isset($program) && $program!=null){
					$bil_applicant->where("ap.ap_prog_code = '".$program."'");
				}
				if(isset($preference) && $preference!=''){
					$bil_applicant->where("ap.ap_preference ='".$preference."'");
				}
	 			if(isset($academic_year) && $academic_year!=''){
		             	$bil_applicant->where("at.at_academic_year  = '".$academic_year."'");
				}
				
	    		//echo $bil_applicant;		    
				$row_bil = $db->fetchAll($bil_applicant);
				return count($row_bil);
	}
	
}	
?>