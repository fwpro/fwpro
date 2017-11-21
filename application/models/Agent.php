<?php
	class App_Model_Agent {

		public function fnAgentauth($username,$password){

			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$lstrSelect = $lobjDbAdpt->select()
    	   							->from(array("a" => "tbl_agentmaster"),array("a.IdAgentMaster"))
    	   							->where("a.Email = ?",$username)
    	   							->where("a.passwd = ?",md5($password));

			$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
			
			return $larrResult['IdAgentMaster'];
		}
		
	    public function fnEducationdetailviewlist($icnumber) { //Function to get the Program Branch details
		    $lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	 		$select = $lobjDbAdpt->select()
	                ->join(array('a' => 'tbl_studentapplication'),array('IdApplication'))
	                ->where('a.StudentId = ?',$icnumber);
	       $result = $lobjDbAdpt->fetchAll($select);
	       return $result;
     	}
     	
     	
		public function fnGetStudentResultsList($studentid) { //Function to get the Program Branch details
			$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
			$select = $lobjDbAdpt->select()	
				    ->join(array('a' => 'tbl_studentapplication'),array('a.*'))
	                ->join(array('b'=>'tbl_placementtestmarks'),'a.IdApplication  = b.IdApplication')
	                ->join(array('c'=>'tbl_program'),'a.IDCourse = c.IdProgram')
	                ->join(array('d'=>'tbl_placementtestcomponent'),'b.IdPlacementTestComponent = d.IdPlacementTestComponent')
	                ->join(array('e'=>'tbl_placementtest'),'b.IdPlacementTest = e.IdPlacementTest')
	                ->where('a.StudentId = ?',$studentid);
	              //  ->join(array('d'=>'tbl_collegemaster'),'a.idCollege = d.IdCollege');
//	             / echo $select;die();
			$result = $lobjDbAdpt->fetchAll($select);
			return $result;
	     }
		
	}