<?php 
class App_Model_General_DbTable_EmailBlastTemplate extends Zend_Db_Table_Abstract
{
    protected $_name = 'email_blast_tmpl';
	protected $_primary = "ebt_id";
	protected $_subname = 'email_blast_tmpl_content';
	protected $_subprimary = "ebtc_id";
	
	public function getData($id=null){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
	    		->from(array('ebt'=>$this->_name));
		
		
	    if($id!=null){
			$select->where('ebt.ebt_id = '.$id );
			
			$row = $db->fetchRow($select);
			
			//get content
			if($row){
				
				$select = $db->select()
						->from(array('ebt'=>$this->_subname))
						->where('ebt.ebtc_ebt_id =?',$id);
				$content = $db->fetchAll($select);
				
				if($content){
					$row['content'] = $content;
				}else{
					$row['content'] = null;
				}						
			}
			
		}else{
			$row = $db->fetchAll($select);
			
			//get content
			if($row){
				foreach($row as $index=>$tmplt){
					$select = $db->select()
							->from(array('ebt'=>$this->_subname))
							->where('ebt.ebtc_ebt_id =?',$tmplt['ebt_id']);
					
					$content = $db->fetchAll($select);
				
					if($content){
						$row[$index]['content'] = $content;
					}else{
						$row[$index]['content'] = null;
					}
				}
			}
		}           
		 
	   
	    if(!$row){
	    	return null;
	    }else{
	    	return $row;
	    }	
		           
	}
	
	public function getDataCollege($collegeId){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db->select()
				->from(array('ebt'=>$this->_name))
				->joinLeft(array('u'=>'tbl_user'), 'u.iduser = ebt.ebt_create_by', array())
				->joinLeft(array('ts'=>'tbl_staffmaster'), 'ts.IdStaff = u.IdStaff', array('ebt_create_by_name'=>'Fullname'))
				->where('ts.idCollege = '.$collegeId);
		
		$row = $db->fetchAll($select);
			
		//get content
		if($row){
			foreach($row as $index=>$tmplt){
				$select = $db->select()
						->from(array('ebt'=>$this->_subname))
						->where('ebt.ebtc_ebt_id =?',$tmplt['ebt_id']);
				
				$content = $db->fetchAll($select);
			
				if($content){
					$row[$index]['content'] = $content;
				}else{
					$row[$index]['content'] = null;
				}
			}
		}
				
		
			
	
		if(!$row){
			return null;
		}else{
			return $row;
		}
		 
	}
	
	public function getContent($id,$language){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('ebtc'=>$this->_subname))
					->where('ebtc.ebtc_ebt_id = ?',$id)
					->where('ebtc.ebtc_language = ?',$language);
			
		$row = $db->fetchRow($select);
		
		if(!$row){
			return null;
		}else{
			return $row;
		}
		
	}
	
	public function getPaginateData(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
	                ->from(array('ebt'=>$this->_name))
					->joinLeft(array('u'=>'tbl_user'), 'u.iduser = ebt.ebt_create_by', array())
	                ->joinLeft(array('ts'=>'tbl_staffmaster'), 'ts.IdStaff = u.IdStaff', array('ebt_create_by_name'=>'Fullname'));;
		
		return $select;
	}
	
	public function getPaginateDataCollege($collegeId){
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db->select()
		->from(array('ebt'=>$this->_name))
		->joinLeft(array('u'=>'tbl_user'), 'u.iduser = ebt.ebt_create_by', array())
		->joinLeft(array('ts'=>'tbl_staffmaster'), 'ts.IdStaff = u.IdStaff', array('ebt_create_by_name'=>'Fullname'))
		->where('ts.idCollege = '.$collegeId);
	
		return $select;
	}
	
	public function insert(array $data) {
		
		
		if(!isset($data['ebt_create_by'])){
			$auth = Zend_Auth::getInstance();
			$data['ebt_create_by'] = $auth->getIdentity()->iduser;
		}
		
		if(!isset($data['ebt_create_date'])){
			$data['ebt_create_date'] = date('Y-m-d H:i:s'); 
		}
			
		
		return parent::insert($data);
	}
	
	
	public function update(array $data,$where){
		
		if(!isset($data['ebt_update_by'])){
			$auth = Zend_Auth::getInstance();
			$data['ebt_update_by'] = $auth->getIdentity()->iduser;
		}
		
		if(!isset($data['ebt_update_date'])){
			$data['ebt_update_date'] = date('Y-m-d H:i:s'); 
		}
		
		return parent::update($data, $where);
	}
	
	
	public function deleteData($id){
		$this->delete($this->_primary .' =' . (int)$id);
	}
}
?>