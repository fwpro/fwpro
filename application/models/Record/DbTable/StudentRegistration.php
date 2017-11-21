<?php


class App_Model_Record_DbTable_StudentRegistration extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_studentregistration';
	protected $_primary = "IdStudentRegistration";
	
	
	public function getData($appl_id=0){
		
		if($appl_id!=0){
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('sr'=>$this->_name))					
					->where('sr.IdApplication = ?',$appl_id)
					->where('sr.profileStatus = 92'); //Active
					
			$stmt = $db->query($select);						
			$row = $stmt->fetch();		
		}else{
			$row = $this->fetchAll();
			$row=$row->toArray();
		}
		
		if(!$row){
			throw new Exception("There is No Information Found");
		}
		return $row;
	}
	
	
	public function getStudentInfo($id=0){
			
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from(array('sr'=>$this->_name))
				->joinLeft(array('ap'=>'student_profile'),'ap.appl_id=sr.IdApplication',array('appl_fname','appl_mname','appl_lname'))
				->joinLeft(array('p'=>'tbl_program'),'p.IdProgram=sr.IdProgram',array('ArabicName','ProgramName','ProgramCode'))
				->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake=sr.IdIntake',array('intake'=>'IntakeDefaultLanguage','IntakeId'))
				->joinLeft(array('d'=>'tbl_definationms'),'d.idDefinition=sr.profileStatus',array('StudentStatus'=>'DefinitionDesc'))
				->joinLeft(array('pm'=>'tbl_programmajoring'),'pm.IDProgramMajoring=sr.IDProgramMajoring',array('majoring'=>'BahasaDescription'))
				->joinLeft(array('b'=>'tbl_branchofficevenue'),'b.IdBranch=sr.IdBranch',array('BranchName'))
				->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=p.IdCollege',array('CollegeName'=>'ArabicName','c.IdCollege'))
				->joinLeft(array('sm'=>'tbl_staffmaster'),'sm.IdStaff=sr.AcademicAdvisor',array('AcademicAdvisor'=>'FullName',"FrontSalutation","BackSalutation"))
				->where('sr.IdStudentRegistration = ?',$id)
				->where('sr.profileStatus = 92'); //Active
				
		$row = $db->fetchRow($select);						
		
		return $row;
	}
	
	
/*
	 * This function to get course registered by semester.
	 */
	public function getCourseRegisteredBySemester($registrationId,$idSemesterMain,$idBlock=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		$sql = $db->select()
                        ->from(array('sr' => 'tbl_studentregistration'), array('registrationId'))  
                        ->joinLeft(array('srs'=>'tbl_studentregsubjects'),'srs.IdStudentRegistration = sr.IdStudentRegistration')
                        ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=srs.IdSubject',array('SubjectName','subjectMainDefaultLanguage','BahasaIndonesia','CreditHours','SubCode'))                     
                        ->where('sr.IdStudentRegistration = ?', $registrationId)
                        ->where('srs.IdSemesterMain = ?',$idSemesterMain);   
                                           
        if(isset($idBlock) && $idBlock != ''){ //Block 
        	$sql->where("srs.IdBlock = ?",$idBlock);
        	$sql->order("srs.BlockLevel");
        }  

     
             
        $result = $db->fetchAll($sql);
        return $result;
	}
	
	
	/*
	 * This function to get course registered by semester.
	 */
	public function getCourseRegisteredByBlock($registrationId,$idBlock=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		 $sql = $db->select()
                        ->from(array('sr' => 'tbl_studentregistration'), array('registrationId'))  
                        ->joinLeft(array('srs'=>'tbl_studentregsubjects'),'srs.IdStudentRegistration = sr.IdStudentRegistration')   
                        ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=srs.IdSubject',array('SubjectName','subjectMainDefaultLanguage','BahasaIndonesia','CreditHours','SubCode'))                  
                        ->where('sr.IdStudentRegistration  = ?', $registrationId)
                        ->where("srs.IdBlock = ?",$idBlock)
                        ->order("srs.BlockLevel");
                      
        $result = $db->fetchAll($sql);
        return $result;
	}
	
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	public function getStudentLevelByProgram($program_id, $intake=array(),$level_min=null,$studentstatus=92){
	
		$db = Zend_Db_Table::getDefaultAdapter();
			
		$sql = $db->select()
		->from(array('sr' => 'tbl_studentregistration'), array('student_id'=>'IdStudentRegistration','nim'=>'registrationId','intake_id'=>'IdIntake'))
		->joinLeft(array('ap'=>'student_profile'),'ap.appl_id=sr.IdApplication',array('name' => new Zend_Db_Expr("CONCAT_WS(' ', appl_fname,appl_mname,appl_lname )"), 'nationality'=>'appl_nationality'))
		->joinLeft(array('itk'=>'tbl_intake'),'itk.IdIntake = sr.IdIntake', array('intake_name'=>'IntakeId'))
				->where('sr.IdProgram  = ?', $program_id)
				->where('sr.profileStatus  = ?',$studentstatus)
				->order("sr.registrationId");
	
		if($intake){
		  $sql->where('sr.IdIntake in ('.implode(",",$intake).')');
		}
		
		$result = $db->fetchAll($sql);
	
		return $result;
	
	}
	
}

?>