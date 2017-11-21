<?php 

class App_Model_Student_DbTable_StudentProfile extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'student_profile';
	protected $_primary = "id";
	
	public function addData($data){		
	   $id = $this->insert($data);
	   return $id;
	}
	
    public function deleteStudentData($id){		
	  $this->delete('appl_id = '. (int)$id);
	}
	
}

?>