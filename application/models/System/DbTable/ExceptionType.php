<?php 
class App_Model_System_DbTable_ExceptionType extends Zend_Db_Table_Abstract
{
    protected $_name = 'sys009_exception_type';
	protected $_primary = 'id';
	
	
	
	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		if($id!=0){

	        $select = $db->select()
	                 ->from($this->_name)
	                 ->where($this->_primary.' = ' .$id);
			                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetch();
	        
		}else{
			$select = $db->select()
	                 ->from($this->_name);
			                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
	        	       
		}
			
		
		return $row;
	}
	
	public function getInfo($module_id,$controller_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
	                 ->from($this->_name)
	                 ->where('module_id = '. $module_id)
	                 ->where('controller_id ='. $controller_id);
			                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
	        
	        	        
	        return $row;
	}
	
	
	public function addData($data){
		
		$auth = Zend_Auth::getInstance(); 
		
		$data = array(
			'module_id' => $data['module_id'],
			'controller_id' => $data['controller_id'],
			'type' => $data['type'],			
			'createddt' => date("Y-m-d H:i:s"),
      	 	'createdby' => $auth->getIdentity()->id,
		);
			
		$this->insert($data);
	}
	
	public function updateData($data,$id){
		$data = array(
			'type' => $data['type']			
		);
		
		$this->update($data, $this->_primary . ' = ' . (int)$id);
	}
	
	public function deleteData($id){
		$this->delete($this->_primary .' =' . (int)$id);
	}
	

}
?>