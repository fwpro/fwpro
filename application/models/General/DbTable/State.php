<?php 
class App_Model_General_DbTable_State extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_state';
	protected $_primary = "idState";
	
	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		if($id!=0){

	        $select = $db->select()
	                 ->from(array('s'=>$this->_name))
	                 ->where('s.'.$this->_primary.' = ' .$id)
	                 ->joinLeft(array('c'=>'tbl_countries'),"c.idCountry = s.idCountry",array('country_name'=>'c.CountryName'));
					                     
	        $stmt = $db->query($select);
	        $row = $stmt->fetch();
	        
			if(!$row){
				throw new Exception("There is No Data");
			}
        
		}else{
			$select = $db->select()
	                 ->from(array('s'=>$this->_name))
	                 ->joinLeft(array('c'=>'tbl_countries'),"c.idCountry = s.idCountry",array('country_name'=>'c.CountryName'));
			
	        $row = $db->fetchAll($select);
	        
		}
		
		return $row;
	}
	
	public function getPaginateData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$selectData = $db ->select()
							->from(array('s'=>$this->_name))
							->join(array('c'=>'tbl_countries'),
									"c.idCountry = s.idCountry",
									array('country_name'=>'c.CountryName')
							);
		return $selectData;
	}
	
	public function getStateOnly(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$selectData = $db ->select()
							->from(array('s'=>$this->_name))
							;
		$stmt = $db->query($selectData);
        $row = $stmt->fetchAll();
        
		return $row;
	}
	
	public function getStateArray(){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectCountry = $db ->select()
							->from(array('s'=>$this->_name))
							->join(array('c'=>'tbl_countries'),
									"c.idCountry = s.idCountry",
									array('country_name'=>'c.CountryName','country_arab_name'=>'c.CountryName')
							)
							->group('s.idCountry');
							
		$stmt = $db->query($selectCountry);
        $row = $stmt->fetchAll();
	    
        $i=0;
        foreach ($row as $country){
	    	$row[$i]['state'] = $this->getState($country['country_id']);
	    	$i++;	    	
	    }
        
        return $row;
	}
	
	public function getState($country_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db ->select()
							->from(array('s'=>$this->_name))
							->where('idCountry = ?', $country_id)		
							->where('Active=1')					
							->order('s.StateName ASC');
							
		$stmt = $db->query($select);
        $row = $stmt->fetchAll();
        
        return $row;
	}
	
	public function addData($data){
		$data = array(
			'name' => $data['name'],
			'name_arabic' => $data['name_arabic'],
			'country_id' => $data['country_id']
		);
			
		$this->insert($data);
	}
	
	public function updateData($data,$id){
		$data = array(
			'name' => $data['name'],
			'name_arabic' => $data['name_arabic'],
			'country_id' => $data['country_id']
		);
			
		$this->update($data, $this->_primary . ' = ' . (int)$id);
	}
	
	public function deleteData($id){
		$this->delete($this->_primary .' =' . (int)$id);
	}
	
	public function selectState(){    	
    	
          	
    	$select = $this->select()->from($this, array('id', 'name'));
        $rowSet = $this->fetchAll($select);
		$arraySet = $rowSet->toArray();
		$list = array();
		$list = array("Please Select..");
		foreach ($arraySet as $value) {
			$list[$value['id']] = $value['name'];
		}
        return $list;
    }
}
?>