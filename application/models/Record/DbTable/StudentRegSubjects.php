<?php


class App_Model_Record_DbTable_StudentRegSubjects extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_studentregsubjects';
	protected $_primary = "IdStudentRegSubjects";
	
	
	public function getActiveRegisteredCourse($idSemesterMain,$IdStudentRegistration){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		 $sql = $db->select()
                        ->from(array('sr' => 'tbl_studentregistration'), array('registrationId'))  
                        ->joinLeft(array('srs'=>'tbl_studentregsubjects'),'srs.IdStudentRegistration = sr.IdStudentRegistration')
                        ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=srs.IdSubject',array('SubjectName','subjectMainDefaultLanguage','BahasaIndonesia','CreditHours','SubCode'))   
                        ->joinLeft(array('cgs'=>'course_group_schedule'),'idGroup=srs.IdCourseTaggingGroup')                  
                        ->where('sr.IdStudentRegistration = ?', $IdStudentRegistration)
                        ->where('srs.IdSemesterMain = ?',$idSemesterMain)
                        ->where('srs.Active=1')
		 				->where('srs.subjectlandscapetype != 2');
                        
                        
                        
       $sql .= 					"ORDER BY CASE cgs.sc_day 
                                 WHEN 'Monday' THEN 1
                                 WHEN 'Tuesday' THEN 2
                                 WHEN 'Wednesday' THEN 3
                                 WHEN 'Thursday' THEN 4
                                 WHEN 'Friday' THEN 5
                                 WHEN 'Saturday' THEN 6
                                 WHEN 'Sunday' THEN 7
                                 ELSE 8
                                 END ";
                          
         
        $result = $db->fetchAll($sql);
        return $result;
	}
	
	public function getSemesterSubjectRegistered($idSemesterMain,$IdStudentRegistration,$active=1,$subjectType=array(1,3)){
		$db = Zend_Db_Table::getDefaultAdapter();
			
		$sql = $db->select()
		->from(array('srs'=>'tbl_studentregsubjects'))
		->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=srs.IdSubject',array('SubjectName','subjectMainDefaultLanguage','BahasaIndonesia','CreditHours','SubCode'))
		->where('srs.IdStudentRegistration = ?', $IdStudentRegistration)
		->where('srs.IdSemesterMain = ?',$idSemesterMain)
		->where('srs.Active=?',$active)
		->where('srs.subjectlandscapetype in (?)', $subjectType);;
		 
		$result = $db->fetchAll($sql);
		return $result;
		
	}
	
	
	public function getTotalCreditHoursActiveRegisteredCourse($idSemesterMain,$IdStudentRegistration){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		$sql = $db->select()
                        ->from(array('srs' => 'tbl_studentregsubjects'),array())   
                        ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=srs.IdSubject', array('total'=>new Zend_Db_Expr('SUM(CreditHours)')))                       
                        ->where('srs.IdStudentRegistration = ?', $IdStudentRegistration)
                        ->where('srs.IdSemesterMain = ?',$idSemesterMain)
                        ->where('srs.Active=1');
         
        $result = $db->fetchRow($sql);
        return $result["total"];
	}
	
}

?>