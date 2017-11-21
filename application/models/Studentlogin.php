<?php
	class App_Model_Studentlogin {
		public function fnStudentauth($username,$password){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a" => "tbl_studentregistration"),array("a.*"))
    	   							->join(array("b" => "tbl_studentapplication"),'a.IdApplication = b.IdApplication')
    	   							->where("b.EmailAddress = ?",$username)
    	   							->where("a.Psswrd = ?",$password);

			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			
			return $larrResult;
		}
	public function fngetexamdetails($rigid){
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("cm"=>"tbl_studentapplication"),array("CONCAT_WS(' ',IFNULL(cm.fName,''),IFNULL(cm.mName,''),IFNULL(cm.lName,'')) as StudentName"))
       								->join(array("pbl"=>"tbl_studentregistration"),'pbl.IdApplication=cm.IdApplication',array("pbl.*"))
       								->join(array("sa"=>"tbl_landscape"),'sa.IdLandscape=pbl.IdLandscape',array("sa.*"))
       								->join(array("p"=>"tbl_program"),'p.IdProgram=sa.IdProgram',array("p.*"))
       								->joinLeft(array("g"=>"tbl_collegemaster"),'cm.StudentCollegeId=g.IdCollege',array("g.CollegeName"))       								
    	   							->where("pbl.IdStudentRegistration = ?",$rigid); 
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
			
			return $larrResult;
		}
	}
	