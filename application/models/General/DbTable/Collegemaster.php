<?php 
class App_Model_General_DbTable_Collegemaster extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_collegemaster';
	protected $_primary = "IdCollege";
	
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			$row = $this->fetchRow($this->_primary.' = ' .$id);
		}else{
			$row = $this->fetchAll();
		}
		
		if(!$row){
			return null;
		}else{
			return $row->toArray();	
		}
	}
	
	/*
	 * function utk selection shj
	 */
	public function getFaculty(){
		
		$session = new Zend_Session_Namespace('sis');
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
	                ->from(array('c'=>$this->_name));

	    if($session->IdRole == 311 || $session->IdRole == 386 || $session->IdRole == 298){ //FACULTY DEAN, FACULTY FINANCE atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("c.IdCollege='".$session->idCollege."'");		
	    }                                
        
        $row = $db->fetchAll($select);
		return $row;
	}
	
	/*
	 * Tak kira role ape semua orang boleh tgk
	 */
	
	public function getAllFaculty(){
		
		$session = new Zend_Session_Namespace('sis');
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
	                ->from(array('c'=>$this->_name));

	                             
        
        $row = $db->fetchAll($select);
		return $row;
	}
	
	
	
}
?>