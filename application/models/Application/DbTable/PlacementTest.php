<?php

class App_Model_Application_DbTable_PlacementTest extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'appl_placement_head';
	protected $_primary = "aph_id";
		
	public function getData($id=0,$type=null){
		$id = (int)$id;
		
		if($id!=0){
			$db = Zend_Registry::get('dbapp');
			$select = $db->select()
					->from(array('aph'=>$this->_name))
					->joinLeft(array('u'=>'tbl_user'),'u.iduser = aph.aph_create_by', array('aph_create_by_name'=>'fName'))
					->where('aph.aph_id = '.$id);
			
			if($type!=null){
				$select->where('aph.aph_testtype = '.$type);
			}
							
			$row = $db->fetchRow($select);
		}else{
			$db = Zend_Registry::get('dbapp');
			$select = $db->select()
					->from(array('aph'=>$this->_name))
					->joinLeft(array('u'=>'tbl_user'),'u.iduser = aph.aph_create_by', array('aph_create_by_name'=>'fName'));

			if($type!=null){
				$select->where('aph.aph_testtype = '.$type);
			}
			
			$row = $db->fetchAll($select);
		}
		
		return $row;
		
	}
	
	public function getDataFromCode($code=null){
		
		
		if($code!=null){
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('aph'=>$this->_name))
					->joinLeft(array('u'=>'tbl_user'),'u.iduser = aph.aph_create_by', array('aph_create_by_name'=>'lName'))
					->where("aph.aph_placement_code  = '".$code."'");
							
			$row = $db->fetchRow($select);
		}else{
			$row=null;
		}
		
		return $row;
		
	}
	
	public function getPaginateData(){
		$db = Zend_Registry::get('dbapp');
		$selectData = $db ->select()
						->from(array('aph'=>$this->_name))
						->joinLeft(array('u'=>'tbl_user'),'u.idUser = aph.aph_create_by', array('aph_create_by_name'=>'fName'))
             		    ->order('aph.'.$this->_primary.' ASC');
						
		return $selectData;
	}
	
	public function searchPaginate($post = array()){
		$db = Zend_Registry::get('dbapp');
		$selectData = $db ->select()
						->from(array('aph'=>$this->_name))
						->where("aph.aph_placement_code LIKE '%".$post['aph_placement_code']."%'")
						->where("aph.aph_placement_name LIKE '%".$post['aph_placement_name']."%'")
						->where("aph.aph_start_date LIKE '%".$post['aph_start_date']."%'")
						->where("aph.aph_end_date LIKE '%".$post['aph_end_date']."%'")
						->where("aph.aph_effective_date like '%".$post['aph_effective_date']."%'")
						->where("aph.aph_create_date like '%".$post['aph_create_date']."%'")
             		    ->order('aph.'.$this->_primary.' ASC');
						
		return $selectData;
	}
	
	public function addData($postData){
		$auth = Zend_Auth::getInstance(); 
		
		$data = array(
		        'aph_placement_code' => $postData['aph_placement_code'],
		        'aph_placement_name' => $postData['aph_placement_name'],
				'aph_academic_year' => $postData['aph_academic_year'],
				'aph_batch' => $postData['aph_batch'],
				'aph_fees_program' => $postData['aph_fees_program'],
				'aph_fees_location' => $postData['aph_fees_location'],
				'aph_start_date' => date ("Y-m-d H:i:s", strtotime($postData['aph_start_date'])),
				'aph_end_date' => date ("Y-m-d H:i:s", strtotime($postData['aph_end_date'])),
				'aph_effective_date' => date ("Y-m-d H:i:s", strtotime($postData['aph_effective_date'])),
				'aph_create_by' => $auth->getIdentity()->id,
      	 		'aph_create_date' => date("Y-m-d H:i:s"),
				'aph_testtype' => $postData['aph_testtype'],
				);
		
		$this->insert($data);
	}
	
	public function updateData($postData,$id){
		
		$auth = Zend_Auth::getInstance(); 
		
		$data = array(
		        'aph_placement_code' => $postData['aph_placement_code'],
		        'aph_placement_name' => $postData['aph_placement_name'],
				'aph_academic_year' => $postData['aph_academic_year'],
				'aph_batch' => $postData['aph_batch'],
				'aph_fees_program' => $postData['aph_fees_program'],
				'aph_fees_location' => $postData['aph_fees_location'],
				'aph_start_date' => date ("Y-m-d H:i:s", strtotime($postData['aph_start_date'])),
				'aph_end_date' => date ("Y-m-d H:i:s", strtotime($postData['aph_end_date'])),
				'aph_effective_date' => date ("Y-m-d H:i:s", strtotime($postData['aph_effective_date'])),
				'aph_testtype' => $postData['aph_testtype'],
				);
			
		$this->update($data, 'aph_id = '. (int)$id);
	}
	
	public function deleteData($id){
		if($id!=0){
			$this->delete('aph_id = '. (int)$id);
		}
	}
	
	public function getOtherData($idptest){
		$db = Zend_Registry::get('dbapp');
		$selectData = $db ->select()
						->from(array('aph'=>$this->_name))
						->where("aph_id <> ?",$idptest)
						->order('aph.'.$this->_primary.' ASC');
		$data = $db->fetchAll($selectData);
		return $data;
	}	

}

