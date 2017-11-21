<?php

class App_Model_Application_DbTable_PlacementTestProgram extends Zend_Db_Table_Abstract {

	protected $_name = 'appl_placement_program';
	protected $_primary = "app_id";
		
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			$db = Zend_Registry::get('dbapp');
			$select = $db->select()
					->from(array('app'=>$this->_name))
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode = app.app_program_code', array('IdProgram' => 'IdProgram','ProgramName' => 'ProgramName', 'ProgramNameIndonesia' => 'ArabicName'))
					->where('app.app_id = '.$id);

			$row = $db->fetchRow($select);
		}else{
			$db = Zend_Registry::get('dbapp');
			$select = $db->select()
					->from(array('app'=>$this->_name))
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode = app.app_program_code', array('IdProgram' => 'IdProgram','ProgramName' => 'ProgramName', 'ProgramNameIndonesia' => 'ArabicName'));
								
			$row = $db->fetchAll($select);
		}
		
		return $row;
	}
	
	public function getPlacementtestProgramData($placementestCode){
		
		$db = Zend_Registry::get('dbapp');
		$select =  $db->select()
					->from(array('app'=>$this->_name))
					->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode = app.app_program_code', array('IdProgram' => 'IdProgram','ProgramName' => 'ProgramName', 'ProgramNameIndonesia' => 'ArabicName'))
					->where("app.app_placement_code = '".$placementestCode."'");
								
		$row = $db->fetchAll($select);
		
		if($row){
			return $row;
		}else{
			return null;
		}
		
	}
	
	public function addData($postData){
		
		$data = array(
		        'app_placement_code' => $postData['app_placement_code'],
				'app_program_code' => $postData['app_program_code'],
				'app_pass_mark' => $postData['app_pass_mark']
				);
				
		$id=$this->insert($data);
		return $id;
	}
	
	public function updateData($postData,$id){
		
		$data = array(
		        'app_placement_code' => $postData['app_placement_code'],
				'app_program_code' => $postData['app_program_code'],
				'app_pass_mark' => $postData['app_pass_mark']
				);
			
		$this->update($data, 'app_id = '. (int)$id);
	}
	
	public function deleteData($id){
		if($id!=0){
			$this->delete('app_id = '. (int)$id);
		}
	}
	
	public function getPlacementTestProgram($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//get placement test data
		$select = $db->select(array('apt_ptest_code'))
	                 ->from(array('ap'=>'applicant_ptest'))
	                 ->where('ap.apt_at_trans_id = ?', $transaction_id);
	                 
	    $stmt = $db->query($select);
        $placementTestCode = $stmt->fetch();
        
        if($placementTestCode){
	        //get placementest program data
		  	$select = $db->select()
		                 ->from(array('app'=>'appl_placement_program'))
		                 ->joinLeft(array('p'=>'tbl_program'),'p.ProgramCode = app.app_program_code' )
		                 ->where('app.app_placement_code  = ?', $placementTestCode['apt_ptest_code'])
		                 ->order('p.ArabicName ASC');
	
	        $stmt = $db->query($select);
	        $row = $stmt->fetchAll();
	        
	        if($row){
	        	return $row;
	        }else{
	        	return null;
	        }
        }else{
        	return null;
        }
	}
	
	public function changeStatus($id,$changeto){
		if($id!=0){
			$data["app_status"]=$changeto;
			$this->update($data,'app_id = '. (int)$id);
		}
	}	

}

