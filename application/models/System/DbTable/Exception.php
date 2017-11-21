<?php 
class App_Model_System_DbTable_Exception extends Zend_Db_Table_Abstract
{
    protected $_name = 'sys008_exception';
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
	        
	        if($row){
	        	$row =  $row->toArray();
	        }
		}
			
		return $row;
	}
	
	public function getInfo($user_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		    $select = $db->select()
	                 ->from(array('e'=>$this->_name))	 
	                 ->join(array('et'=> 'sys009_exception_type'),'et.id = e.exception_type_id')	
	                 ->joinLeft(array('c'=> 'sys005_controller'),'c.id = et.controller_id',array('c_name'=>'c.name')) 
	                 ->joinLeft(array('m'=> 'sys002_module'),'m.id = et.module_id',array('m_name'=>'m.name'))                    	        
	                 ->where('user_id ='. $user_id);
	                 
	       	                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
	        
	        	        
	        return $row;
	}
	
	
	public function addData($data){
		
		$auth = Zend_Auth::getInstance(); 
		
		$data = array(
			'user_id' => $data['user_id'],
			'exception_type_id' => $data['exception_type_id'],
			'position' => $data['position'],			
			'createddt' => date("Y-m-d H:i:s"),
      	 	'createdby' => $auth->getIdentity()->id,
		);
			
		$this->insert($data);
	}
	
	public function updateData($data,$id){
		
		$auth = Zend_Auth::getInstance(); 
		
		$data = array(			
			'exception_type_id' => $data['exception_type_id'],
			'position' => $data['position'],			
			'createddt' => date("Y-m-d H:i:s"),
      	 	'createdby' => $auth->getIdentity()->id,
		);
		
		$this->update($data, $this->_primary . ' = ' . (int)$id);
	}
	
	public function deleteData($id){
		$this->delete($this->_primary .' =' . (int)$id);
	}
	

}
?>