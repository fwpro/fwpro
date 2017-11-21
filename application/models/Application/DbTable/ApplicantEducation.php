<?php 
class App_Model_Application_DbTable_ApplicantEducation extends Zend_Db_Table_Abstract
{
    protected $_name = 'applicant_education';
	protected $_primary = 'ae_id';
	
	public function getData($appl_id, $txn_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	     $select = $db ->select()
					->from($this->_name)
					->where("ae_appl_id = '".$appl_id."'")
					->where("ae_transaction_id = '".$txn_id."'");
       
        $row = $db->fetchRow($select);
        
        if($row){
        	return $row;
        }else{
        	return null;
        }
	}
	
	public function getDataByapplID($appl_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	     $select = $db ->select()
					->from($this->_name)
					->where("ae_appl_id = '".$appl_id."'")
					->order("ae_id desc");
       
        $row = $db->fetchRow($select);
        
        if($row){
        	return $row;
        }else{
        	return null;
        }
	}
	
	
	public function addData($data){		
	   $id = $this->insert($data);
	   return $id;
	}
	
	public function updateData($data,$id){
		 $this->update($data, $this->_primary .' = '. (int)$id);
	}
	
	
	public function getEducationDetail($transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ae'=>$this->_name),array())
					->joinLeft(array('aed'=>'applicant_education_detl'),'aed.aed_ae_id = ae.ae_id',array('aed_sem1','aed_sem2','aed_sem3','aed_sem4','aed_sem5','aed_sem6','aed_average'))					
					->joinLeft(array('ss'=>'school_subject'),'ss.ss_id=aed.aed_subject_id ',array('subject_english'=>'ss.ss_subject','subject_bahasa'=>'ss.ss_subject_bahasa','ss_id'=>'ss.ss_id'))
					->where("ae_transaction_id = '".$transaction_id."'");
       
        $row = $db->fetchAll($select);
        
		if($row){
        	return $row;
        }else{
        	return null;
        }
        
	}
	
	public function getEducationDetailApplId($appl_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ae'=>$this->_name),array())
					->joinLeft(array('aed'=>'applicant_education_detl'),'aed.aed_ae_id = ae.ae_id',array('aed_sem1','aed_sem2','aed_sem3','aed_sem4','aed_sem5','aed_sem6','aed_average'))					
					->joinLeft(array('ss'=>'school_subject'),'ss.ss_id=aed.aed_subject_id ',array('subject_english'=>'ss.ss_subject','subject_bahasa'=>'ss.ss_subject_bahasa','ss_id'=>'ss.ss_id'))
					->where("ae_appl_id = '".$appl_id."'")
					->order('ae_id desc');

        $row = $db->fetchAll($select);
        
		if($row){
        	return $row;
        }else{
        	return null;
        }
        
	}
	
public function getAverageMark($appl_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ae'=>$this->_name))
					->joinLeft(array('aed'=>'applicant_education_detl'),'aed.aed_ae_id = ae.ae_id',array('aed_average'))
					->where("ae.ae_appl_id = '".$appl_id."'")
					->where("aed.aed_average != ''");
       
				
        $education = $db->fetchAll($select);
        
	        $total_subject=count($education);
			$total_mark=0;
			$everage='';
			
			if(isset($total_subject) && ($total_subject!=0)){
				foreach ($education as $e){
					$total_mark = ceil($total_mark)+ ceil($e["aed_average"]);
				}
				$everage = $total_mark/$total_subject;
			}
		
			return $everage;
	}
	
	
    /**	 * 
	 * This function is used for selection status only. In order cater education details with 1 profile (appl_id) change on 10/1/2013
	 * @param int $appl_id
	 * @param int $transaction_id
	 */
	public function getSelectionAverageMark($appl_id,$transaction_id){
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		
	     $select = $db ->select()
					->from(array('ae'=>$this->_name))
					->joinLeft(array('aed'=>'applicant_education_detl'),'aed.aed_ae_id = ae.ae_id',array('aed_average'))
					->where("ae.ae_appl_id = '".$appl_id."'")	
					->where("ae.ae_transaction_id = '".$transaction_id."'")									
					->where("aed.aed_average != ''");					
					
         $education = $db->fetchAll($select);           
          $total_subject=count($education);
                  
         	
         if($total_subject==0){
        	
        		 $select = $db ->select()
					->from(array('ae'=>$this->_name))
					->joinLeft(array('aed'=>'applicant_education_detl'),'aed.aed_ae_id = ae.ae_id',array('aed_average'))
					->where("ae.ae_appl_id = '".$appl_id."'")
					->where("aed.aed_average != ''")
					->order("ae.ae_id desc");
					
         		$education = $db->fetchAll($select);   
        }
        
      
			$total_subject=count($education);
			$total_mark=0;
			$everage='';
			
			if(isset($total_subject) && ($total_subject!=0)){
				foreach ($education as $e){
					$total_mark = ceil($total_mark)+ ceil($e["aed_average"]);
				}
				$everage = $total_mark/$total_subject;
			}
		
		return $everage;
	}
	
	
	
	public function getSchool($appl_id,$transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ae'=>$this->_name))								
					->joinLeft(array('sm'=>'school_master'),'sm.sm_id=ae.ae_institution')
					->where("ae_transaction_id = '".$transaction_id."'")
					->where("ae.ae_appl_id = '".$appl_id."'");

        $row = $db->fetchRow($select);
        
        
        if(!$row){
        	
        	  $select = $db ->select()
					->from(array('ae'=>$this->_name))								
					->joinLeft(array('sm'=>'school_master'),'sm.sm_id=ae.ae_institution')					
					->where("ae.ae_appl_id = '".$appl_id."'");

        	  $row = $db->fetchRow($select);
        }
        
      
        if($row){
        	return $row;
        }else{
        	return null;
        }
	}
	
	
	public function getEducationInfo($appl_id,$transaction_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
	    $select = $db ->select()
					->from(array('ae'=>$this->_name))								
					->joinLeft(array('sm'=>'school_master'),'sm.sm_id=ae.ae_institution',array('sm_name'))
					->joinLeft(array('sd'=>'school_discipline'),'sd.smd_code=ae.ae_discipline_code',array('smd_code','smd_desc'))
					->where("ae_transaction_id = '".$transaction_id."'")
					->where("ae.ae_appl_id = '".$appl_id."'");

        $row = $db->fetchRow($select);
        
        
        if(!$row){
        	
        	  $select = $db ->select()
					->from(array('ae'=>$this->_name))								
					->joinLeft(array('sm'=>'school_master'),'sm.sm_id=ae.ae_institution')		
					->joinLeft(array('sd'=>'school_discipline'),'sd.smd_code=ae.ae_discipline_code',array('smd_code','smd_desc'))			
					->where("ae.ae_appl_id = '".$appl_id."'");

        	  $row = $db->fetchRow($select);
        }
        
      
        if($row){
        	return $row;
        }else{
        	return null;
        }
	}
	
	
}
?>