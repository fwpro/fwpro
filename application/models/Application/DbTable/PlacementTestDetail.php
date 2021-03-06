<?php

class App_Model_Application_DbTable_PlacementTestDetail extends Zend_Db_Table_Abstract {

	protected $_name = 'appl_placement_detl';
	protected $_primary = "apd_id";
		
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			$db = Zend_Registry::get('dbapp');
			$select = $db->select()
					->from(array('apd'=>$this->_name))
					->join(array('ac'=>'appl_component'),'ac.ac_comp_code = apd.apd_comp_code',array('ac_comp_name'=>'ac_comp_name'))
					->where('ac.ac_id = '.$id);
							
			$row = $db->fetchRow($select);
		}else{
			$db = Zend_Registry::get('dbapp');
			$select = $db->select()
						->from(array('apd'=>$this->_name))
						->join(array('ac'=>'appl_component'),'ac.ac_comp_code = apd.apd_comp_code',array('ac_comp_name'=>'ac_comp_name'));
								
			$row = $db->fetchAll($select);
		}
		
		return $row;
		
	}
	
	public function getPlacementTestData($placementTestCode){
		
		$db = Zend_Registry::get('dbapp');
		$select = $db->select()
					->from(array('apd'=>$this->_name))
					->join(array('ac'=>'appl_component'),'ac.ac_comp_code = apd.apd_comp_code',array('ac_comp_name'=>'ac_comp_name'))
					->where('apd.apd_placement_code = ?',$placementTestCode);
							
		$row = $db->fetchAll($select);
		
		if($row){
			return $row;	
		}else{
			return null;
		}
	}
	
	public function getPlacementTestComponentData($placementtest_code, $component_code){
		
		$db = Zend_Registry::get('dbapp');
		$select = $db->select()
					->from(array('apd'=>$this->_name))
					//->join(array('ac'=>'appl_component'),'ac.ac_comp_code = apd.apd_comp_code',array('ac_comp_name'=>'ac_comp_name'))
					->where('apd.apd_placement_code = ?',$placementtest_code)
					->where('apd.apd_comp_code = ?',$component_code);
							
		$row = $db->fetchRow($select);
		
		if($row){
			return $row;	
		}else{
			return null;
		}
	}
	
	public function addData($postData){
		
		$data = array(
	        'apd_placement_code' => $postData['apd_placement_code'],
			'apd_comp_code' => $postData['apd_comp_code'],
			'apd_total_question' => $postData['apd_total_question'],
			'apd_questno_start' => $postData['apd_questno_start'],
			'apd_questno_end' => $postData['apd_questno_end']
		);
				
		$this->insert($data);
	}
	
	public function updateData($postData,$id){
		
		$data = array(
	        'apd_placement_code' => $postData['apd_placement_code'],
			'apd_comp_code' => $postData['apd_comp_code'],
			'apd_total_question' => $postData['apd_total_question'],
			'apd_questno_start' => $postData['apd_questno_start'],
			'apd_questno_end' => $postData['apd_questno_end']
		);
		
		$this->update($data, 'apd_id = '. (int)$id);
	}
	
	public function deleteData($data,$id){
		if($id!=0){
			$this->update($data, 'ac_id = '. (int)$id);
		}
	}

	public function deletePlacementTestData($placementtest_code){
		if($placementtest_code!=null){
			$this->delete("apd_placement_code = '". $placementtest_code."'");
		}
	}
	
	public function getPlacementTestComp($placementTestCode,$comptype=1){
		
		$db = Zend_Registry::get('dbapp');
		
		if($comptype==1) $addwhere="ac.ac_test_type=$comptype  or ac.ac_test_type=6";
		else $addwhere="ac.ac_test_type=$comptype";
		
		$sql = "Select ap.*,ac_comp_name_bahasa,ac_id from ".$this->_name." ap 
				inner join appl_component as ac 
				on ap.apd_comp_code = ac.ac_comp_code
				where ($addwhere) and 
				ap.apd_placement_code='$placementTestCode';
				;
				";				
		
		$row = $db->fetchAll($sql);
		
		if($row){
			return $row;	
		}else{
			return null;
		}
	}	
}

