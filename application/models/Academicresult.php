<?php
	class App_Model_Academicresult {

		
	    public function fnCheckRegistrationId($registrationid) { //Function to get the Program Branch details
		    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	 		$select = $lobjDbAdpt->select()
	                ->join(array('a' => 'tbl_studentregistration'),array('IdStudentRegistration'))
	                ->where('a.registrationId = ?',$registrationid);
	       $result = $lobjDbAdpt->fetchAll($select);
	       return $result;
     	}
     	
     	
		public function fnGetStudentAcademicResultsList($registrationid,$takemarks) { //Function to get the Program Branch details
     		$consistantresult = 'SELECT MAX(i.IdStudentRegistration)  from tbl_studentregistration i where i.IdApplication = d.IdApplication';
     		
     		//$appealresults = 'SELECT MAX(y.IdAppeal) from tbl_appeal y INNER JOIN tbl_subjectmarksentry m where m.IdStudentRegistration = y.IdRegistration AND m.idSubject = y.IdSubject';
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()	;
			if($takemarks == 0) {
				$select->from(array('x' => 'tbl_verifiermarks'),array( 'AVG(x.verifiresubjectmarks) AS verifiresubjectmarks','x.idVerifierMarks','x.idverifier'));
			} elseif($takemarks == 1) {
				$select->from(array('x' => 'tbl_verifiermarks'),array( 'x.verifiresubjectmarks','x.idVerifierMarks','x.idverifier'));
			}	
			$select->join(array('a' => 'tbl_subjectmarksentry'),'x.idSubjectMarksEntry = a.idSubjectMarksEntry',array('a.idSubjectMarksEntry'))
	                //->join(array('b'=>'tbl_marksentrysetup'),'a.idSubject  = b.IdSubject',array('b.IdMarksEntrySetup','b.IdStaff as verifierstaff','b.Verification'));
	                ->join(array('c'=>'tbl_studentregistration'),'a.IdStudentRegistration = c.IdStudentRegistration',array('c.registrationId','c.IdStudentRegistration','c.IdSemestersyllabus'))
	                ->join(array('d'=>'tbl_studentapplication'),'c.IdApplication = d.IdApplication',array('d.FName','d.MName','d.LName','d.IdApplication','d.IDCourse'))
	                ->join(array('j'=>'tbl_landscape'),'c.IdLandscape = j.IdLandscape',array('j.IdLandscape','j.LandscapeType'))
	                ->join(array('f'=>'tbl_program'),'j.IdProgram = f.IdProgram',array('f.IdProgram','f.ProgramName','f.ShortName'))
	                ->join(array('e'=>'tbl_staffmaster'),'a.idStaff = e.IdStaff',array('e.IdStaff','e.FirstName','e.SecondName','e.ThirdName'))
	                ->join(array('i'=>'tbl_subjectmaster'),'a.idSubject = i.IdSubject',array('i.IdSubject','i.SubjectName','i.ShortName','i.SubCode'))
                	->join(array('k'=>'tbl_marksdistributiondetails'),'a.IdMarksDistributionDetails = k.IdMarksDistributionDetails',array('k.IdMarksDistributionDetails','k.ComponentName','k.PassMark','k.TotalMark','k.Weightage'))
                	->join(array('y'=>'tbl_marksdistributionmaster'),'k.IdMarksDistributionMaster = y.IdMarksDistributionMaster',array('y.IdMarksDistributionMaster','y.Name'))
					//->joinLeft(array('z'=>'tbl_appeal'),'a.IdStudentRegistration = z.IdRegistration AND a.idSubject = z.IdSubject AND k.IdMarksDistributionDetails =z.IdMarksDistributionDetails AND y.IdMarksDistributionMaster = z.IdMarksDistributionMaster AND z.IdAppeal= '.new Zend_Db_Expr('('.$appealresults.')').'',array('z.IdAppeal'))
					//->joinLeft(array('l'=>'tbl_appealmarksentry'),'z.IdAppeal = l.IdAppeal',array('l.IdAppealEntry','l.NewMarks','l.DateOfRemarks'))
	                ->where("c.IdStudentRegistration = ?",new Zend_Db_Expr('('.$consistantresult.')'))
	                ->where('c.registrationId = ?',$registrationid)
       				->where("d.Offered = 1")
       				->where("d.Termination = 0")
       				->where("d.Accepted = 1");
			if($takemarks == 0) {
	       		$select->group("k.IdMarksDistributionDetails");
	       		$select->group("y.IdMarksDistributionMaster");
			} elseif($takemarks == 1) {
				$select->where("x.Rank = 1 OR x.Rank = 0");
			}
			$select ->order('i.IdSubject')
					->order('y.IdMarksDistributionMaster')
					->order('k.IdMarksDistributionDetails');	
       		//echo $select;die();
			$result = $lobjDbAdpt->fetchAll($select);
			return $result;
	     }
	     
	     public function fnAddappealList($larrformData){
	     	$db = Zend_Db_Table::getDefaultAdapter();
			$table = "tbl_appeal";				
			$larrformData['AppealCode']=0;
			$larrformData['AppealStatus']=1;
			unset ($larrformData['programid']);
			unset ($larrformData['Save']);
	     	$db->insert($table,$larrformData);	
	     	$insertId = $db->lastInsertId('tbl_appeal','IdAppeal');	
	     	return $insertId;
	     }
	     
	     
	   public function fnGenerateCodes($lintlastId,$IdRegistration,$IdSubject,$programid){	

		$db 	= 	Zend_Db_Table::getDefaultAdapter();	
		$idUniversity=1;
		$select =   $db->select()
				->  from('tbl_config')
				->	where('idUniversity  = ?',$idUniversity);
		$result = 	$db->fetchRow($select);		
		$sepr	=	$result['AppealCodeSeparator'];
		$str	=	"AppealCodeField";
		for($i=1;$i<=4;$i++){
			$check = $result[$str.$i];
			switch ($check){
				case 'ProgramId':
				  $code	= $programid;
				  break;				  
				case 'SubjectId':
				  $code	= $IdSubject;
				  break;
				case 'StudentRegId':
					$code	= $IdRegistration;
				  break;
				case 'UniquId': 
					$code	= $lintlastId;
				    break;
				default:
				   break;
			}
			if($i == 1) $accCode 	 =  $code;
			else 		$accCode	.=	$sepr.$code;
		}	
	 	$data = array('AppealCode' => $accCode);
		$where['IdAppeal  = ? ']= $lintlastId;		
		return $db->update('tbl_appeal', $data, $where);	
	}

	public function fnGetUniversityId($registrationid) { //Function for the view University 
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$select = $lobjDbAdpt->select()
			->from(array('a' => 'tbl_studentregistration'),array('a.*'))
			->join(array('b'=>'tbl_studentapplication'),'a.IdApplication = b.IdApplication')
			->join(array('c'=>'tbl_collegemaster'),'b.idCollege = c.IdCollege')
            ->where('a.registrationId = ?',$registrationid);
    	$result = $lobjDbAdpt->fetchRow($select);
		return $result;
    }
	
	
}
	
	