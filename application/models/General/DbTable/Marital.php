<?php 
class App_Model_General_DbTable_Marital extends Zend_Db_Table_Abstract
{
    protected $_name = 'g011_marital_status';
	protected $_primary = "id";
	
	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		if($id!=0){

	        $select = $db->select()
	                ->from(array('b'=>$this->_name))
	                ->where('b.'.$this->_primary.' = ' .$id);
			                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetch();
	        
			if(!$row){
				throw new Exception("There is No Data");
			}
        
		}else{
			$select = $db->select()
	                ->from(array('b'=>$this->_name));
			                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
	        
	        if(!$row){
	        	$row =  $row->toArray();
	        }
		}
		
		
		
		return $row;
	}
	
	public function getPaginateData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
	                ->from(array('b'=>$this->_name))
					->joinLeft(array('c'=>'g001_country'),
								"c.id = b.country_id",
								array('country_name'=>'c.name'))
					->joinLeft(array('s'=>'g002_state'),
								"s.id = b.state_id",
								array('state_name'=>'s.name'));
		
		return $select;
	}
	
}
?>