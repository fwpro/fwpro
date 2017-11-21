<?php 
class App_Model_General_DbTable_SchoolType extends Zend_Db_Table_Abstract
{
    protected $_name = 'school_type';
	protected $_primary = "st_id";
	
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
}
?>