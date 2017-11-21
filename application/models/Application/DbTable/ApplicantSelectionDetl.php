<?php 
class App_Model_Application_DbTable_ApplicantSelectionDetl extends Zend_Db_Table_Abstract
{
    protected $_name = 'applicant_selection_detl';
	protected $_primary = "asd_id";
	
	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		    $select = $db ->select()
						->from($this->_name)
						->where($this->_primary .' = '.$id);

			
	        $row = $db->fetchRow($select);
		    return $row;
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
	
/**
	 * Funtion: To get list of selection nomor 
	 * @param int $type
	 */
	
	public function getInfo($type=null,$intake=null,$period=null){
		
			$session = new Zend_Session_Namespace('sis');
		
			$db = Zend_Db_Table::getDefaultAdapter();
		
		    $select = $db ->select()
						  ->from(array('asd'=>$this->_name))
						  ->joinLeft(array('i'=>'tbl_intake'),'i.IdIntake=asd.asd_intake_id')
						  ->joinLeft(array('p'=>'tbl_academic_period'),'p.ap_id=asd.asd_period_id')
						  ->joinLeft(array('c'=>'tbl_collegemaster'),'c.IdCollege=asd.asd_faculty_id')
						  ->order("asd_id DESC");
						
			if($type){	
				$select->where("asd_type = '".$type."'");
			}
			
			if($intake){	
				$select->where("asd_intake_id = '".$intake."'");
			}
			
			if($period){	
				$select->where("asd_period_id = '".$period."'");
			}
			

			if($session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("asd.asd_faculty_id='".$session->idCollege."'");		
	    	} 
			//echo $select;
									
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
		    return $row;
	}
}

?>