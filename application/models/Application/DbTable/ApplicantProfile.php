<?php 

class App_Model_Application_DbTable_ApplicantProfile extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'applicant_profile';
	protected $_primary = "appl_id";
	protected $_db;
	
	function App_Model_Application_DbTable_ApplicantProfile(){
		$this->_db = Zend_Registry::get('dbapp');
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
	
	public function uniqueEmail($email){
	
		$select = $this->_db->select()
					  ->from($this->_name)
					  ->where("appl_email = ?", $email);

		$row = $this->_db->fetchRow($select);	
		 
		if($row){
		 	return false;
		}else{
			return true;	
		}
	}
	
	public function verifyData($username,$password){
	
		$select = $this->_db->select()
					  ->from($this->_name)
					  ->where("appl_email = ?", $username)
					  ->where("appl_password = ?", $password);
					  
		 $row = $this->_db->fetchRow($select);	
		 return $row;
	}
	
	public function getForgotPasswordData($email,$dob){
	
		$select = $this->_db->select()
					  ->from($this->_name)
					  ->where("appl_email = ?", $email)
					  ->where("appl_dob = ?", $dob);
					  
		 $row = $this->_db->fetchRow($select);	
		 
		 if($row){
		 	return $row;
		 }else{
		 	return null;	
		 }
		 
	}
	
	public function getData($id=""){
	
		$select = $this->_db->select()
					  ->from($this->_name);
					  
		if($id)	{			
			 $select->where("appl_id ='".$id."'");
			 $row = $this->_db->fetchRow($select);	
			 
		}	else{			
			$row = $this->_db->fetchAll($select);	
		}	  
		
		 return $row;
	}
	
	
	public function getProfile ($id=""){
	
		$select = $this->_db->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('admission_type'=>'at_appl_type'))
					  ->joinleft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id',(array('education'=>'ae.ae_discipline_code')))
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',(array('fee'=>'apt.apt_fee_amt','bill_no'=>'apt.apt_bill_no','currency'=>'apt.apt_currency','schedule_id'=>'apt.apt_aps_id')))
					  ->where("at.at_status = 'APPLY'")
					  ->where("ap.appl_id ='".$id."'");
		$row = $this->_db->fetchRow($select);	
		return $row;
	}
	
	public function search($name="", $id="", $id_type=0, $program_id=0){
		
		$select = $this->_db->select()
						->from(array('a'=>$this->_name))
						->where("a.ARD_PROGRAM != 0 and a.ARD_OFFERED = 1")
						->join(array('p'=>'r006_program'),'p.id = a.ARD_PROGRAM',array('program_code'=>'code'))
						->join(array('mp'=>'r005_program_main'),'mp.id = p.program_main_id',array('main_name'=>'name'));
						
		if($name!=""){
			$select->where("ARD_NAME like '%".$name."%'");	
		}						
		
		if($id!=""){
			$select->where("ARD_IC like '%".$id."%'");	
		}
		
		if($id_type!=0){
			$select->where("ARD_TYPE_IC = ".$id_type);	
		}
		
		if($program_id!=0){
			$select->where("ARD_PROGRAM = ".$program_id);	
		}
		
		$stmt = $this->_db->query($select);
	    $row = $stmt->fetchAll();
	    
	    return $row;
	}
	
	public function getPaginateData(){
		
		 $select = $this->_db->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id')
					  ->joinleft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id',(array('education'=>'ae.ae_discipline_code')))
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',(array('fee'=>'apt.apt_fee_amt','bill_no'=>'apt.apt_bill_no','currency'=>'apt.apt_currency','schedule_id'=>'apt.apt_aps_id')));
						
		return $select;
	}
	
	public function verify($transaction_id,$billing_no,$pin_no){
		
			 $select = $this->_db->select()
					  ->from(array('ap'=>$this->_name))	
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id')				 
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')	
					  ->joinLeft(array('apb'=>'appl_pin_to_bank'),'apb.billing_no=apt.apt_bill_no')				  
					  ->where("at.at_trans_id ='".$transaction_id."'")
					  ->where("apt.apt_bill_no = '".$billing_no."'")
				      ->where("apb.REGISTER_NO = '".$pin_no."'");	//	entry yg belum pakai								
       
        $row = $this->_db->fetchRow($select);
		return $row;
	}
	
		public function viewkartu($transaction_id){
		
			 $select = $this->_db->select()
					  ->from(array('ap'=>$this->_name))	
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id')				 
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id')	
					  ->joinLeft(array('apb'=>'appl_pin_to_bank'),'apb.billing_no=apt.apt_bill_no')				  
					  ->where("at.at_trans_id ='".$transaction_id."'");	//	entry yg belum pakai								
       
        $row = $this->_db->fetchRow($select);
		return $row;
	}

public function getAllProfile ($id=""){
	
		 $select = $this->_db->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('admission_type'=>'at_appl_type'))
					  ->joinleft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id',(array('education'=>'ae.ae_discipline_code')))
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',(array('fee'=>'apt.apt_fee_amt','bill_no'=>'apt.apt_bill_no','currency'=>'apt.apt_currency','schedule_id'=>'apt.apt_aps_id')))	
					  ->joinleft(array('p'=>'tbl_city'),'p.idCity=ap.appl_city',array('CityName'=>'p.CityName'))
					  ->joinleft(array('s'=>'tbl_state'),'s.idState=ap.appl_state',array('StateName'=>'s.StateName'))					  			
					  ->where("at.at_trans_id ='".$id."'");
		$row = $this->_db->fetchRow($select);	
		return $row;
	}
	
	public function getPaginateDatabyProgram($condition=null){
		
		 $select = $this->_db->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date','status'=>'at.at_status'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'school_master'),'sm.sm_id=ae.ae_institution',array('school'=>'sm.sm_name'));
					   
					   if($condition!=null){
					   		if($condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					  		if($condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}
					   		if(isset($condition["status"]) && $condition["status"]!=''){
								$select->where("at.at_status  = '".$condition["status"]."'");	
							}
					   }
					   
		//echo $select;			  
		return $select;
	}
	
	public function getDatabyProgram($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date','status'=>'at.at_status'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'));
					   
					   if($condition!=null){
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}
					  		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
					   			$select->where("at.at_trans_id ='".$condition["transaction_id"]."'");
					  		}
					  		
					   		if(isset($condition["academic_year"]) && $condition["academic_year"]!=''){
					   			$select->where("at.at_academic_year ='".$condition["academic_year"]."'");
					  		}
					  		
					  		if(isset($condition["status"]) && $condition["status"]!=''){
								$select->where("at.at_status  = '".$condition["status"]."'");
					  		}
					   		if(isset($condition["period"]) && $condition["period"]!=''){	
					   			$period = explode('/',$condition["period"]);
					   							   		
								$select->where("MONTH(at.at_submit_date) = '".$period[0]."'");
								$select->where("YEAR(at.at_submit_date) = '".$period[1]."'");
					  		}
					   }
					   
		
		// echo $select;
		 
		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
			$row = $db->fetchRow($select);
		}else{		   
			$row = $db->fetchAll($select);		
		}		  
		return $row;
	}
	
	
	public function getDeanSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date','status'=>'at.at_status'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->where("at.at_selection_status = 0");
					  
					  
					   
					   if($condition!=null){
					   	
					   		if(isset($condition["faculty_id"]) && $condition["faculty_id"]!=''){
					   			$select->where("p.IdCollege ='".$condition["faculty_id"]."'");
					   		}
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}
					  		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
					   			$select->where("at.at_trans_id ='".$condition["transaction_id"]."'");
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
					   
		
		
		// echo $select;
		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
			$row = $db->fetchRow($select);
		}else{		   
			$row = $db->fetchAll($select);		
		}		  
		return $row;
	}
	
	
	public function getRectorSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->where("at.at_selection_status = 1");
					   
					   if($condition!=null){
					  		if(isset($condition["faculty_id"]) && $condition["faculty_id"]!=''){
					   			$select->where("p.IdCollege ='".$condition["faculty_id"]."'");
					   		}
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}
					  		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
					   			$select->where("at.at_trans_id ='".$condition["transaction_id"]."'");
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
					   
					   
		//echo $select; 
		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
			$row = $db->fetchRow($select);
		}else{		   
			$row = $db->fetchAll($select);		
		}		  
		return $row;
	}
	
	
	public function getApprovalSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->where("at.at_selection_status = 2");
					   
					   if($condition!=null){
					   		if(isset($condition["faculty_id"]) && $condition["faculty_id"]!=''){
					   			$select->where("p.IdCollege ='".$condition["faculty_id"]."'");
					   		}
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}
					  		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
					   			$select->where("at.at_trans_id ='".$condition["transaction_id"]."'");
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
					   
		
		
	  //echo $select;
		if(isset($condition["transaction_id"]) && $condition["transaction_id"]!=''){
			$row = $db->fetchRow($select);
		}else{		   
			$row = $db->fetchAll($select);		
		}		  
		return $row;
	}
	
	
	public function getResultSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
					   ->where("at.at_selection_status = 3");
					   
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
					   		/*if(isset($condition["period"]) && $condition["period"]!=''){	
					   			$period = explode('/',$condition["period"]);
					   							   		
								$select->where("MONTH(at.at_submit_date) = '".$period[0]."'");
								$select->where("YEAR(at.at_submit_date) = '".$period[1]."'");
					  		}*/
					  		if(isset($condition["period"]) && $condition["period"]!=''){	
					   			$select->where("at_period = '".$condition["period"]."'");
					  		}
					  		
					  	   
					   }
			   
		$row = $db->fetchAll($select);	  
		return $row;
	}
	
	public function getAgentPaginateData($condition=null){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id')
					  ->joinleft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id',(array('education'=>'ae.ae_discipline_code')))
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',(array('fee'=>'apt.apt_fee_amt','bill_no'=>'apt.apt_bill_no','currency'=>'apt.apt_currency','schedule_id'=>'apt.apt_aps_id')));
					  
					  
	  					if($condition!=null){
					   		if(isset($condition["agent_id"]) && $condition["agent_id"]!=''){
					   			$select->where("at.agent_id ='".$condition["agent_id"]."'");
					   		}
					   		
					  	   
					   }			 
						
		return $select;
	}
	
	public function getAgentData($condition=null){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id')
					  ->joinleft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id',(array('education'=>'ae.ae_discipline_code')))
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',(array('fee'=>'apt.apt_fee_amt','bill_no'=>'apt.apt_bill_no','currency'=>'apt.apt_currency','schedule_id'=>'apt.apt_aps_id')));
					  
					  
	  					if($condition!=null){
					   		if(isset($condition["agent_id"]) && $condition["agent_id"]!=''){
					   			$select->where("at.agent_id ='".$condition["agent_id"]."'");
					   		}
					   		
					  	   
					   }			 
						
		return $select;
	}
	
	
	public function getStatusSelection($condition=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					   ->from(array('ap'=>$this->_name))
					   ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('transaction_id'=>'at.at_trans_id','applicantID'=>'at.at_pes_id','submit_date'=>'at.at_submit_date','selection_status'=>'at.at_selection_status','status'=>'at.at_status'))
					   ->joinleft(array('apr'=>'applicant_program'),'apr.ap_at_trans_id=at.at_trans_id')
					   ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=apr.ap_prog_code',array('program_id'=>'p.IdProgram','program_name'=>'p.ProgramName','program_name_indonesia'=>'p.ArabicName','program_code'=>'p.ProgramCode'))
					   ->joinLeft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id')
					   ->joinLeft(array('sm'=>'tbl_schoolmaster'),'sm.idSchool=ae.ae_institution',array('school'=>'sm.SchoolName'))
					   ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('faculty'=>'c.ArabicName'))
					   ->order("at.at_pes_id DESC");
					  
					   
					   if($condition!=null){
					   		if(isset($condition["faculty"]) && $condition["faculty"]!=''){
					   			$select->where("p.IdCollege ='".$condition["faculty"]."'");
					   		}
					   		if(isset($condition["program_code"]) && $condition["program_code"]!=''){
					   			$select->where("apr.ap_prog_code ='".$condition["program_code"]."'");
					   		}
					   		if(isset($condition["admission_type"]) && $condition["admission_type"]!=''){
					   			$select->where("at.at_appl_type ='".$condition["admission_type"]."'");
					   		}		
					   		if(isset($condition["academic_year"]) && $condition["academic_year"]!=''){
					   			$select->where("at.at_academic_year ='".$condition["academic_year"]."'");
					  		}					  		
					  		
					  		if(isset($condition["period"]) && $condition["period"]!=''){	
					   			$select->where("at_period = '".$condition["period"]."'");
					  		}					  		
					  		if(isset($condition["selection_status"]) && $condition["selection_status"]!=''){
								$select->where("at.at_selection_status  = '".$condition["selection_status"]."'");
					  		}
					   	
					  		
					  	   
					   }
		//echo $select;	   
		$row = $db->fetchAll($select);	  
		return $row;
	}
	
	public function getTransProfile ($id="",$transid=""){
	 
		$db = Zend_Db_Table::getDefaultAdapter();
		
		 $select = $db ->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id AND at.at_trans_id='.$transid,array('admission_type'=>'at_appl_type'))
					  ->joinleft(array('ae'=>'applicant_education'),'ae.ae_appl_id=ap.appl_id',(array('education'=>'ae.ae_discipline_code')))
					  ->joinleft(array('sd'=>'school_discipline'),'sd.smd_code=ae.ae_discipline_code',array('discipline'=>'sd.smd_desc'))
					  ->joinleft(array('apt'=>'applicant_ptest'),'apt.apt_at_trans_id=at.at_trans_id',(array('fee'=>'apt.apt_fee_amt','bill_no'=>'apt.apt_bill_no','currency'=>'apt.apt_currency','schedule_id'=>'apt.apt_aps_id')))
					  //->where("at.at_status = 'APPLY'")
					  ->where("ap.appl_id ='".$id."'");

		$row = $db->fetchRow($select);	
		return $row;
	}

	public function mergeProfile($mginfo){
		  
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$dataap["at_appl_id"]=$mginfo["maintain_appl_id"];	
		$sqlt = "Update applicant_transaction set at_appl_id='".$mginfo["maintain_appl_id"]."' where at_trans_id = '".$mginfo["chg_trans_id"]."'"; 
		$db->query($sqlt);
		//$db->update('applicant_transaction',$dataap, "at_trans_id = ".$mginfo["chg_trans_id"]);
		//echo $sql."<hr>";
		
		$data_advpayment["advpy_appl_id"]=$mginfo["maintain_appl_id"];
		$sqlap = "Update advance_payment set advpy_appl_id='".$mginfo["maintain_appl_id"]."' where advpy_fomulir = '".$mginfo["chg_formulir"]."'"; 
		$db->query($sqlap);
		//$db->update("advance_payment",$data_advpayment,"advpy_fomulir = ".$mginfo["chg_formulir"]);
		//echo $sql."<hr>";
		
		$data_invoice["appl_id"]=$mginfo["maintain_appl_id"];
		$sql1 = "Update invoice_main set appl_id='".$mginfo["maintain_appl_id"]."' where no_fomulir = '".$mginfo["chg_formulir"]."'"; 
		$db->query($sql1);
		//$db->update("invoice_main",$data_invoice,"no_fomulir = ".$mginfo["chg_formulir"]);
		//echo $sql."<hr>";
		
		$data_payment["appl_id"]=$mginfo["maintain_appl_id"];
		$sql2 = "Update payment_main set appl_id='".$mginfo["maintain_appl_id"]."' where payer = '".$mginfo["chg_formulir"]."'"; 
		$db->query($sql2);
		//$db->update("payment_main",$data_payment,"payer = ".$mginfo["chg_formulir"]);
		//echo $sql."<hr>";
		
		$data_cn["appl_id"]=$mginfo["maintain_appl_id"];
		$sql3 = "Update credit_note set appl_id='".$mginfo["maintain_appl_id"]."' where cn_fomulir = '".$mginfo["chg_formulir"]."'"; 
		$db->query($sql3);
		//$db->update("credit_note",$data_cn,"cn_fomulir = ".$mginfo["chg_formulir"]);
		//echo $sql."<hr>";
		
		$data_refund["rfd_appl_id"]=$mginfo["maintain_appl_id"];
		$sql4 = "Update refund set rfd_appl_id='".$mginfo["maintain_appl_id"]."' where rfd_fomulir = '".$mginfo["chg_formulir"]."'"; 
		$db->query($sql4);
		//$db->update("refund",$data_refund,"rfd_fomulir = ".$mginfo["chg_formulir"]);
		//echo $sql."<hr>";
		
		//Utk Disabled Profile tu//
		/*$sql5="Update applicant_profile set appl_email = '".$mginfo["mail_archive"]."_disabled' where appl_id=".$mginfo["archive_appl_id"];
		$db->query($sql5); */
		
		$log["m_formulir1"]=$mginfo["chg_formulir"];
		$log["m_formulir2"]=$mginfo["maintain_formulir"];
		$log["mail_maintain"]=$mginfo["mail_maintain"];
		$log["mail_archive"]=$mginfo["mail_archive"];
		$log["from_appl_id"]=$mginfo["archive_appl_id"];
		$log["to_appl_id"]=$mginfo["maintain_appl_id"];
		$log["mergedate"]=$mginfo["mergedate"];
		$log["mergeby"]=$mginfo["mergeby"];
		
		$db->insert("merge_log",$log); 
		
	} 
	
	public function getProfileByFormulir($noformulir){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 $select = $db ->select()
					  ->from(array('ap'=>$this->_name))
					  ->joinleft(array('at'=>'applicant_transaction'),'at.at_appl_id=ap.appl_id',array('admission_type'=>'at_appl_type','at_trans_id','at_pes_id','at_status'))
					  ->joinleft(array('af'=>'applicant_family'),'af.af_appl_id=ap.appl_id and af_relation_type=21',array('af_name'))
					  ->where("at.at_pes_id =?",$noformulir)
					  ; 
			//echo $select."<br>";
			$row = $db->fetchRow($select);	
			return $row;
	}
}
?>