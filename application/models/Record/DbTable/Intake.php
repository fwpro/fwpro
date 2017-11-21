<?php
class App_Model_Record_DbTable_Intake extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_intake';
    protected $_primary = 'IdIntake';
	
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from($this->_name)
					->where($this->_name.".".$this->_primary .' = '. $id);
					
				$row = $db->fetchRow($select);
		}else{
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from($this->_name)
					->order('ApplicationStartDate');
								
			$row = $db->fetchAll($select);
		}
		
		
		
		if(!$row){
			throw new Exception("There is No Data");
		}
		
		return $row;
	}
	
	public function getPaginateData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from($this->_name)
				->order('ApplicationStartDate DESC');
								
		return $select;
	}

	public function updateData($id,$formData)
    {
        $data = array(
            'ap_code' => $formData['ap_code'],
            'ap_desc' => $formData['ap_desc']
        );
        
        $this->update($data, $this->_primary .' = '. (int)$id);
    }
    
	public function getCurrentIntake(){

		$dateNow = date('Y-m-d');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from($this->_name)
				->where("'".$dateNow."'" . " between ".$this->_name.".ApplicationStartDate and ".$this->_name.".ApplicationEndDate");

		$row = $db->fetchRow($select);
				
		if(!$row){
			//throw new Exception("There is No Data");
			$row = null;
		}
		
		return $row;
	}
	
	public function getNextIntake(){

		$dateNow = date('Y-m-d');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		echo $select = $db->select()
				->from($this->_name)
				->where($this->_name.".ApplicationStartDate > '".$dateNow."'")
				->order($this->_name.".IntakeId");

		$row = $db->fetchRow($select);
				
		if(!$row){
			//throw new Exception("There is No Data");
			$row = null;
		}
		
		return $row;
	}
	
	public function getPreviousIntake(){
	
		$dateNow = date('Y-m-d');
	
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
		->from($this->_name)
		->where($this->_name.".ApplicationStartDate < '".$dateNow."'")
		->order($this->_name.".ApplicationStartDate DESC");
	
		$row = $db->fetchRow($select);
	
		if(!$row){
			//throw new Exception("There is No Data");
			$row = null;
		}
	
		return $row;
	}
	
	public function fngetlatestintake(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()->from(array('a' => $this->_name),array("key" => "a.IdIntake" , "value" => "a.IntakeId"))
		//->where('a.ApplicationStartDate >= ?',date('Y-m-d'))
		->order("a.IntakeId desc");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}
	
	public function getIntakeWithStudent($profileStatus="92",$programId=null){ //student profile status active
	  
	    $db = Zend_Db_Table::getDefaultAdapter();
	    
		$select = $db->select()
		          ->distinct()
		          ->from(array('i'=>$this->_name))
		          ->join(array('sr'=>'tbl_studentregistration'), 'sr.IdIntake  = i.IdIntake and sr.profileStatus = '.$profileStatus, array());
		
		if($programId){
		  $select->where('sr.IdProgram = ?',$programId);
		}
	
		$row = $db->fetchAll($select);
		
		//count student
		if($row){
		  
		  foreach ($row as $index=>$intake){
		    
		      $select = $db->select()
		                ->from(array('sr'=>'tbl_studentregistration'), array('count(*) as student_count'))
		                ->where('sr.profileStatus = ?', $profileStatus)
		                ->where('sr.IdIntake = ?', $intake['IdIntake']);
		      
		      if($programId){
		        $select->where('sr.IdProgram = ?',$programId);
		      }
		    
		      $row_count = $db->fetchRow($select);
		      
		      if($row_count){
		        $row[$index]['student_count'] = $row_count['student_count'];
		      }
		  }
		}
	
		if(!$row){
			$row = null;
		}
	
		return $row;
	  
	}
}