<?php 

class App_Model_Record_DbTable_Studentsemesterstatus extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_studentsemesterstatus';
	protected $_primary = "idstudentsemsterstatus";

			
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
	

	public function getRegisteredBlock($registrationId){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		$sql = $db->select()
                   ->from(array('s' => $this->_name))   
                   ->joinleft(array('b'=>'tbl_landscapeblock'),'b.idblock=s.IdBlock',array('blockname','semester_level'=>'semester'))      
                   ->joinleft(array('sm'=>'tbl_semestermaster'),'sm.IdSemesterMaster=s.IdSemesterMain',array('SemesterMainName','SemesterMainCode'))       
                   ->where('s.IdStudentRegistration = ?',$registrationId) 
                   ->order("s.idstudentsemsterstatus")
                   ->group('s.IdBlock'); 
                  
        $result = $db->fetchAll($sql);
        return $result;
	}
	
	
	public function getRegisteredSemester($registrationId){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		$sql = $db->select()
                   ->from(array('s' => $this->_name))  
                   ->joinleft(array('sm'=>'tbl_semestermaster'),'sm.IdSemesterMaster=s.IdSemesterMain',array('SemesterMainName','SemesterMainCode'))  
                   ->joinLeft(array('d'=>'tbl_definationms'),'d.idDefinition=s.studentsemesterstatus',array('semester_status'=>'DefinitionDesc')) 
                   ->joinLEft(array('u'=>'tbl_user'),'u.iduser=s.UpdUser',array('lName','mName','fName'))    
                   ->where('s.IdStudentRegistration = ?',$registrationId)
                   ->order("s.idstudentsemsterstatus")
                   ->group('s.IdSemesterMain'); 
                  
        $result = $db->fetchAll($sql);
        return $result;
	}
	
	
	public function getData($idstudentsemsterstatus=0){
		
		if($idstudentsemsterstatus!=0){
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('s'=>$this->_name))					
					->where('s.idstudentsemsterstatus = ?',$idstudentsemsterstatus);
					
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
	
	
	public function getSemesterInfo($idstudentsemsterstatus=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		 
		$sql = $db->select()
                   ->from(array('s' => $this->_name))  
                   ->joinleft(array('sm'=>'tbl_semestermaster'),'sm.IdSemesterMaster=s.IdSemesterMain',array('SemesterMainName','SemesterMainCode'))  
                   ->where('s.idstudentsemsterstatus = ?',$idstudentsemsterstatus);                   
                  
        $result = $db->fetchRow($sql);
        return $result;
	}
	
	
}

?>