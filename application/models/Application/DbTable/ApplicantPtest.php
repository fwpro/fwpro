<?php 
class App_Model_Application_DbTable_ApplicantPtest extends Zend_Db_Table_Abstract
{
    protected $_name = 'applicant_ptest';
	protected $_primary = 'apt_id';
	
	public function getData($id=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	     $select = $db ->select()
					->from($this->_name);
	     
	     if($id){
				$select->where('apt_id = ?', $id);
	     }										
       
        $row = $db->fetchRow($select);
		return $row;
	}
	
	public function getUnmarkedData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('a'=>$this->_name))
					->join(array('t'=>'applicant_transaction'),"t.at_trans_id  = a.apt_at_trans_id and t.at_status in ('PROCESS','CLOSE')")
					->join(array('p'=>'applicant_profile'),'p.appl_id  = t.at_appl_id');										
		
		//echo $select;
        $row = $db->fetchAll($select);
		return $row;
	}
	
	public function getPtest($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	     $select = $db ->select()
					->from($this->_name)
					->where('apt_at_trans_id =?', $transaction_id);										
       
        $row = $db->fetchRow($select);
        
        if($row){
        	return $row;	
        }else{
        	return null;
        }
		
	}
	
	public function getApplicantCount($schedule_id, $room_id){
		$schedule_id = (int)$schedule_id;
		$room_id = (int)$room_id;
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	     $select = $db ->select()
					->from(array('apt'=>$this->_name),array('count(*) as bill'))
					->where('apt.apt_aps_id =?', $schedule_id)
					->where('apt.apt_room_id =?', $room_id);										
       
        $row = $db->fetchRow($select);
        
        if($row){
        	return $row['bill'];	
        }else{
        	return 0;
        }
	}
	
	public function addData($data){		
	   $id = $this->insert($data);
	   return $id;
	}
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	public function getInfo($appl_id,$ptest_code){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from($this->_name)
					->where("apt_at_trans_id='".$appl_id."'")
					->where("apt_ptest_code='".$ptest_code."'");										
       
        $row = $db->fetchRow($select);
		return $row;
	}
	
	public function updateInfo($data,$transaction_id,$scid){
		 $this->update($data, 'apt_at_trans_id  = '. (int)$transaction_id);
	}
	
	/*public function getPlacementTestProgram($transaction_id){
		//query ni kalo pakai salah tau kene guna yg lain
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    echo $select = $db ->select()
					->from(array('ap'=>$this->_name))					
					
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode=aprog.app_program_code',array('program_name'=>'p.ProgramName','program_code'=>'p.ProgramCode'))
					->joinLeft(array('d'=>'tbl_departmentmaster'),'p.idFacucltDepartment=d.IdDepartment',array('faculty'=>'d.DeptName'))
					->where("ap.apt_at_trans_id  = '".$transaction_id."'")				
					->order("app.app_preference Asc");
					
        $stmt = $db->query($select);
        $row = $stmt->fetchAll();
		return $row;
	}*/
	
	
	public function getScheduleInfo($transaction_id,$apt_id=''){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('apt'=>$this->_name))
					->joinLeft(array('aps'=>'appl_placement_schedule'),'aps.aps_id  = apt.apt_aps_id',array('aps_id'=>'aps.aps_id','aps_location_id'=>'aps.aps_location_id','aps_test_date'=>'aps.aps_test_date','aps_start_time'=>'aps.aps_start_time'))
					->joinLeft(array('al'=>'appl_location'),'al.al_id=aps.aps_location_id',array('location_name'=>'al_location_name'))
					->where("apt_at_trans_id='".$transaction_id."'");

		if($apt_id!=""){
			 $select->where("apt_id = ?",$apt_id);
		}
       
        $row = $db->fetchRow($select);
        //print_r($row);
		return $row;
	}
	
	public function search($name="", $ptest_code=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('a'=>$this->_name))
					->join(array('t'=>'applicant_transaction'),'t.at_trans_id  = a.apt_at_trans_id')
					->join(array('p'=>'applicant_profile'),'p.appl_id  = t.at_appl_id');
						
		if($name!=""){
			$select->where("concat(p.appl_fname,' ',appl_mname,' ',appl_lname) like '%".$name."%'");	
		}						
		
		if($ptest_code!=""){
			$select->where("a.apt_ptest_code like '%".$ptest_code."%'");	
		}
		
		//echo $select ."<br />";
		
	    $row = $db->fetchRow($select);
	    
	    return $row;
	}
	
	public function searchByName($name="", $ptest_code=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('a'=>$this->_name))
					->join(array('t'=>'applicant_transaction'),'t.at_trans_id  = a.apt_at_trans_id')
					->join(array('p'=>'applicant_profile'),'p.appl_id  = t.at_appl_id');
						
		if($name!=""){
			$select->where("concat(p.appl_fname,' ',appl_mname,' ',appl_lname) like '%".$name."%'");	
		}						
		
		if($ptest_code!=""){
			$select->where("a.apt_ptest_code like '%".$ptest_code."%'");	
		}
		
		//echo $select ."<br />";
		
	    $row = $db->fetchRow($select);
	    
	    return $row;
	}
	
	public function searchByPES($pes="", $scid="", $roomid=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('a'=>$this->_name))
					->join(array('t'=>'applicant_transaction'),'t.at_trans_id  = a.apt_at_trans_id')
					->join(array('p'=>'applicant_profile'),'p.appl_id  = t.at_appl_id');
						
		if($pes!=""){
			$select->where("t.at_pes_id = '".$pes."' or a.apt_no_pes='".$pes."'");	
			//$select->where("a.apt_no_pes='".$pes."'");	
		}						
		
		if($scid!=""){
			$select->where("a.apt_aps_id= ? ",$scid);	
		}

		if($roomid!=""){
			$select->where("a.apt_room_id= ? ",$roomid);	
		}
		//echo $select ."<br />";
		
	    $row = $db->fetchRow($select);
	    
	    return $row;
	}
	
	public function searchByFomulir($fomulir="", $ptest_code=""){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('a'=>$this->_name))
					->join(array('t'=>'applicant_transaction'),'t.at_trans_id  = a.apt_at_trans_id')
					->join(array('p'=>'applicant_profile'),'p.appl_id  = t.at_appl_id');
						
		if($name!=""){
			$select->where("a.apt_bill_no = '".$fomulir."'");	
		}						
		
		if($ptest_code!=""){
			$select->where("a.apt_ptest_code like '%".$ptest_code."%'");	
		}
		
		//echo $select ."<br />";
		
	    $row = $db->fetchRow($select);
	    
	    return $row;
	}
	
	public function generateNoPes($transactionID){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$pdata = $this->getPtest($transactionID);
		$pesno = $pdata["apt_no_pes"];
		
		if($pesno==""){
			
			$sql = "select IntakeId,ap_number,at_period,at_intake from applicant_transaction at 
					inner join tbl_intake as ti on at.at_intake=ti.IdIntake
					inner join tbl_academic_period as ap on ap.ap_id = at.at_period
					where at_trans_id='$transactionID'
			";
			$row = $db->fetchRow($sql);	
			
			//2 angka pertama adalah intake year
			
			$pes[0]=substr($row["IntakeId"],2,2);
			
			
			//Period code mesti berdasarkan schedule date\
			$periodDB = new App_Model_Record_DbTable_AcademicPeriod();
			$sched=$this->getScheduleInfo($transactionID);
			$ptPeriod   = $periodDB->getCurrentPeriod(date("n",strtotime($sched['aps_test_date'])), date("Y",strtotime($sched['aps_test_date'])));
			
			if(strlen($sched["aps_location_id"])==1){
				$pes[1]="0".$sched["aps_location_id"];
			}else{
				$pes[1]=$sched["aps_location_id"];
			}
			
			/* handle problem application yg period nya
			 * yg base on application/transaction(USM only) date*/
			
			$sqlu ="update applicant_transaction set at_period='".$ptPeriod["ap_id"]."'
					where at_trans_id='".$transactionID."'
			";
			
			$db->query($sqlu);
						
			$sql3 = "select count(*)+1 as bil from applicant_ptest ap
					inner join applicant_transaction as at on at.at_trans_id=ap.apt_at_trans_id
					inner join appl_placement_schedule as aps on aps.aps_id  = ap.apt_aps_id
					where ap.apt_no_pes<>'' and at.at_intake=".$row["at_intake"]." 
					and aps.aps_location_id='".$sched["aps_location_id"]."'
					";
					
			$rno = 	$db->fetchRow($sql3);
			
			$strno = 5 - strlen($rno["bil"]);
			$frontno="";
			for($i=0;$i<$strno;$i++){
				$frontno .= "0";
			}
			
			$pes[2]=$frontno.$rno["bil"];	
	
			$pesno = implode("",$pes);
			echo $pesno."<hr>";
			//Pastikan no pes yg di generate unique			
			$unique = false;
			while(!$unique){
				$uniqpes = $this->getPtestbyPesno($pesno);
				if(is_array($uniqpes)){
					$unique = false;
					$rno["bil"]=$rno["bil"]+1;
					
					$strno = 5 - strlen(strval($rno["bil"]));	

					$frontno="";
					for($i=0;$i<$strno;$i++){
						$frontno .= "0";
					}
					$pes[2]=$frontno.$rno["bil"];
					$pesno = implode("",$pes);
					//print_r($pes);
					//echo $pesno."<hr>";
				}else{
					$unique = true;
				}
			}
		
			$sql4="UPDATE applicant_ptest set apt_no_pes='$pesno' where apt_at_trans_id='$transactionID'";
			
			$db->query($sql4);
			
		}
		return $pesno;

	}		
	
	public function getPtestbyPesno($pesno){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = " select * from applicant_ptest where apt_no_pes='$pesno' limit 0,1";
		$row = 	$db->fetchRow($sql);
		return $row;
	}
	
	public function getTpaMark($txnId){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
				->from(array('a'=>$this->_name))
				->join(array('b'=>'appl_placement_head'), 'b.aph_placement_code = a.apt_ptest_code')
				->where('a.apt_at_trans_id = ?',$txnId)
				->where('b.aph_testtype = 1')
				->order('a.apt_id desc');
		
		
		$row = $db->fetchRow($select);
		 
		return $row['apt_mark'];
	}
	
}
?>